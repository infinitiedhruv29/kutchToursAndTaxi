<?php

namespace App\Http\Controllers\Admin;

use App\Http\AdminFeedback;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomPackageRequest;
use App\Http\Requests\Admin\CustomPackageTableRequest;
use App\Models\CustomPackage;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PackageBuilderController extends Controller
{
    private function columns(): array
    {
        return [
            ['data' => 'client', 'label' => 'Client', 'sort' => 'client_name', 'orderable' => true],
            ['data' => 'package_title', 'label' => 'Tour proposal', 'sort' => 'title', 'orderable' => true],
            ['data' => 'duration', 'label' => 'Duration', 'sort' => 'duration', 'orderable' => true],
            ['data' => 'pricing', 'label' => 'Pricing tiers', 'sort' => 'price_2p', 'orderable' => true],
            ['data' => 'created_at', 'label' => 'Date created', 'sort' => 'created_at', 'orderable' => true],
            ['data' => 'actions', 'label' => 'Actions', 'orderable' => false],
        ];
    }

    private function listingQuery(CustomPackageTableRequest $request): Builder
    {
        return CustomPackage::query()
            ->when($request->searchTerm() !== '', function (Builder $query) use ($request): void {
                $search = '%' . $request->searchTerm() . '%';
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('client_name', 'like', $search)
                        ->orWhere('client_phone', 'like', $search)
                        ->orWhere('title', 'like', $search)
                        ->orWhere('highlight', 'like', $search);
                });
            })
            ->orderBy($request->sortColumn(), $request->sortDirection())
            ->orderByDesc('id');
    }

    public function index(CustomPackageTableRequest $request): View
    {
        return view('admin.custom-packages.index', [
            'columns' => $this->columns(),
            'packages' => $this->listingQuery($request)->paginate($request->pageSize())->withQueryString(),
        ]);
    }

    public function data(CustomPackageTableRequest $request): JsonResponse
    {
        $query = $this->listingQuery($request);
        $count = (clone $query)->count();
        $columns = $this->columns();
        $rows = $query->offset($request->offset())->limit($request->pageSize())->get()->map(function (CustomPackage $customPackage) use ($columns): array {
            $row = ['id' => $customPackage->id];
            foreach ($columns as $definition) {
                $column = $definition['data'];
                $row[$column] = view('admin.custom-packages.cell', compact('customPackage', 'column'))->render();
            }

            return $row;
        });

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => CustomPackage::count(),
            'recordsFiltered' => $count,
            'data' => $rows,
        ]);
    }

    private function returnDestination(Request $request): string
    {
        return AdminFeedback::returnDestination($request, 'admin.custom-packages.index', [
            'q' => ['nullable', 'string', 'max:255'],
            'sort' => ['sometimes', Rule::in(['client_name', 'title', 'duration', 'price_2p', 'created_at'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
            'page' => ['sometimes', 'integer', 'min:1', 'max:21474836'],
            'per_page' => ['sometimes', 'integer', Rule::in([15, 25, 50, 100])],
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.custom-packages.form', [
            'customPackage' => new CustomPackage,
            'returnTo' => $this->returnDestination($request),
        ]);
    }

    public function edit(Request $request, CustomPackage $customPackage): View
    {
        return view('admin.custom-packages.form', [
            'customPackage' => $customPackage,
            'returnTo' => $this->returnDestination($request),
        ]);
    }

    public function store(CustomPackageRequest $request): Response|JsonResponse|RedirectResponse
    {
        $customPackage = CustomPackage::create($request->packageData());

        $action = $request->input('submit_action', $request->input('action', 'save'));

        if ($action === 'export') {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Custom package proposal saved! Downloading PDF…',
                    'redirect_url' => route('admin.custom-packages.pdf', $customPackage),
                    'download_url' => route('admin.custom-packages.pdf', $customPackage),
                    'return_url' => $this->returnDestination($request),
                ]);
            }

            return $this->downloadPdf($customPackage);
        }

        return AdminFeedback::success($request, 'Custom package proposal saved successfully.', $this->returnDestination($request));
    }

    public function update(CustomPackageRequest $request, CustomPackage $customPackage): Response|JsonResponse|RedirectResponse
    {
        $customPackage->update($request->packageData());

        $action = $request->input('submit_action', $request->input('action', 'save'));

        if ($action === 'export') {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Custom package proposal updated! Downloading PDF…',
                    'redirect_url' => route('admin.custom-packages.pdf', $customPackage),
                    'download_url' => route('admin.custom-packages.pdf', $customPackage),
                    'return_url' => $this->returnDestination($request),
                ]);
            }

            return $this->downloadPdf($customPackage);
        }

        return AdminFeedback::success($request, 'Custom package proposal updated successfully.', $this->returnDestination($request));
    }

    public function destroy(Request $request, CustomPackage $customPackage): JsonResponse|RedirectResponse
    {
        $customPackage->delete();

        return AdminFeedback::success($request, 'Custom package proposal deleted successfully.', $this->returnDestination($request));
    }

    public function downloadPdf(CustomPackage $customPackage): Response
    {
        $settings = Setting::first();
        $logoBase64 = $this->getLogoBase64($settings);

        $clientName = $customPackage->client_name;
        $clientPhone = $customPackage->client_phone;
        $title = $customPackage->title;
        $duration = $customPackage->duration;
        $highlight = $customPackage->highlight;
        $price2p = $customPackage->price_2p;
        $price4p = $customPackage->price_4p;
        $price6p = $customPackage->price_6p;
        $inclusions = $customPackage->inclusions ?? [];
        $exclusions = $customPackage->exclusions ?? [];
        $itineraries = $customPackage->itineraries ?? [];

        $pdf = Pdf::loadView('admin.custom-packages.pdf', compact(
            'settings',
            'logoBase64',
            'clientName',
            'clientPhone',
            'title',
            'duration',
            'highlight',
            'price2p',
            'price4p',
            'price6p',
            'inclusions',
            'exclusions',
            'itineraries'
        ));

        $filename = Str::slug($clientName . '-' . $title) . '-quote.pdf';

        return $pdf->download($filename);
    }

    private function getLogoBase64(?Setting $settings): ?string
    {
        $primaryColor = !empty($settings?->primary_color)
            ? (str_starts_with($settings->primary_color, '#') ? $settings->primary_color : '#' . $settings->primary_color)
            : '#a8863e';

        // Prefer SVG vector so we can theme it to the exact primary color for PDFs without altering disk assets
        $svgCandidates = [];
        if ($settings?->dark_logo_path && str_ends_with(strtolower($settings->dark_logo_path), '.svg')) {
            $svgCandidates[] = Storage::disk('public')->path($settings->dark_logo_path);
            $svgCandidates[] = public_path($settings->dark_logo_path);
        }
        $svgCandidates[] = public_path('images/logo/logo-gold.svg');
        $svgCandidates[] = public_path('images/logo/logo-black.svg');

        foreach ($svgCandidates as $filePath) {
            if ($filePath && file_exists($filePath) && is_readable($filePath)) {
                try {
                    $svgContent = file_get_contents($filePath);
                    // Dynamically apply the primary color to the vector fill only for PDFs
                    $themedSvg = preg_replace('/fill="(?:\#[a-fA-F0-9]{3,8}|white|black)"/i', 'fill="' . $primaryColor . '"', $svgContent);
                    $themedSvg = preg_replace('/#E7BE67/i', $primaryColor, $themedSvg);

                    return 'data:image/svg+xml;base64,' . base64_encode($themedSvg);
                } catch (\Throwable) {
                    continue;
                }
            }
        }

        // Fallback to raster assets if SVG is unavailable
        $rasterCandidates = [
            public_path('images/logo/logo-gold.png'),
            public_path('images/logo/logo-black.png'),
        ];

        foreach ($rasterCandidates as $filePath) {
            if ($filePath && file_exists($filePath) && is_readable($filePath)) {
                try {
                    $mime = mime_content_type($filePath) ?: 'image/png';
                    $data = file_get_contents($filePath);
                    return 'data:' . $mime . ';base64,' . base64_encode($data);
                } catch (\Throwable) {
                    continue;
                }
            }
        }

        return null;
    }
}
