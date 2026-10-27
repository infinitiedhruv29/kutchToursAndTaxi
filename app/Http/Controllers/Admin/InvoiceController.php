<?php

namespace App\Http\Controllers\Admin;

use App\Http\AdminFeedback;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InvoiceRequest;
use App\Http\Requests\Admin\InvoiceTableRequest;
use App\Models\CustomPackage;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Setting;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    private function columns(): array
    {
        return [
            ['data' => 'invoice_number', 'label' => 'Invoice #', 'sort' => 'invoice_number', 'orderable' => true],
            ['data' => 'customer', 'label' => 'Customer', 'sort' => 'customer_name', 'orderable' => true],
            ['data' => 'invoice_date', 'label' => 'Date', 'sort' => 'invoice_date', 'orderable' => true],
            ['data' => 'items_count', 'label' => 'Items', 'orderable' => false],
            ['data' => 'total_amount', 'label' => 'Total (INR)', 'sort' => 'total_amount', 'orderable' => true],
            ['data' => 'actions', 'label' => 'Actions', 'orderable' => false],
        ];
    }

    private function listingQuery(InvoiceTableRequest $request): Builder
    {
        return Invoice::query()
            ->withCount('items')
            ->when($request->searchTerm() !== '', function (Builder $query) use ($request): void {
                $search = '%' . $request->searchTerm() . '%';
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('invoice_number', 'like', $search)
                        ->orWhere('customer_name', 'like', $search)
                        ->orWhere('customer_contact', 'like', $search)
                        ->orWhere('customer_email', 'like', $search)
                        ->orWhere('customer_gst_number', 'like', $search);
                });
            })
            ->orderBy($request->sortColumn(), $request->sortDirection())
            ->orderByDesc('id');
    }

    public function index(InvoiceTableRequest $request): View
    {
        return view('admin.invoices.index', [
            'columns' => $this->columns(),
            'invoices' => $this->listingQuery($request)->paginate($request->pageSize())->withQueryString(),
        ]);
    }

    public function data(InvoiceTableRequest $request): JsonResponse
    {
        $query = $this->listingQuery($request);
        $count = (clone $query)->count();
        $columns = $this->columns();
        $rows = $query->offset($request->offset())->limit($request->pageSize())->get()->map(function (Invoice $invoice) use ($columns): array {
            $row = ['id' => $invoice->id];
            foreach ($columns as $definition) {
                $column = $definition['data'];
                $row[$column] = view('admin.invoices.cell', compact('invoice', 'column'))->render();
            }

            return $row;
        });

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => Invoice::count(),
            'recordsFiltered' => $count,
            'data' => $rows,
        ]);
    }

    private function returnDestination(Request $request): string
    {
        return AdminFeedback::returnDestination($request, 'admin.invoices.index', [
            'q' => ['nullable', 'string', 'max:255'],
            'sort' => ['sometimes', Rule::in(['invoice_number', 'customer_name', 'invoice_date', 'total_amount'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
            'page' => ['sometimes', 'integer', 'min:1', 'max:21474836'],
            'per_page' => ['sometimes', 'integer', Rule::in([15, 25, 50, 100])],
        ]);
    }

    public function create(Request $request): View
    {
        $invoice = new Invoice([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'invoice_date' => now()->toDateString(),
            'tax_rate' => 5.00,
        ]);

        return view('admin.invoices.form', [
            'invoice' => $invoice,
            'vehicles' => Vehicle::with(['type', 'capacity'])->where('is_published', true)->orderBy('name')->get(),
            'customPackages' => CustomPackage::orderByDesc('id')->get(['id', 'title', 'client_name', 'price_2p', 'price_4p', 'price_6p']),
            'packages' => Package::where('is_published', true)->orderBy('title')->get(['id', 'title', 'price_2p', 'price_4p', 'price_6p']),
            'returnTo' => $this->returnDestination($request),
        ]);
    }

    public function edit(Request $request, Invoice $invoice): View
    {
        $invoice->load(['items.vehicle']);

        return view('admin.invoices.form', [
            'invoice' => $invoice,
            'vehicles' => Vehicle::with(['type', 'capacity'])->where('is_published', true)->orderBy('name')->get(),
            'customPackages' => CustomPackage::orderByDesc('id')->get(['id', 'title', 'client_name', 'price_2p', 'price_4p', 'price_6p']),
            'packages' => Package::where('is_published', true)->orderBy('title')->get(['id', 'title', 'price_2p', 'price_4p', 'price_6p']),
            'returnTo' => $this->returnDestination($request),
        ]);
    }

    public function store(InvoiceRequest $request): Response|JsonResponse|RedirectResponse
    {
        $invoice = DB::transaction(function () use ($request): Invoice {
            $invoice = Invoice::create($request->computedInvoiceData());
            $items = $request->computedItemsData();
            if (!empty($items)) {
                $invoice->items()->createMany($items);
            }

            return $invoice;
        });

        $action = $request->input('submit_action', $request->input('action', 'save'));

        if ($action === 'export') {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Invoice created! Downloading PDF…',
                    'redirect_url' => route('admin.invoices.pdf', $invoice),
                    'download_url' => route('admin.invoices.pdf', $invoice),
                    'return_url' => $this->returnDestination($request),
                ]);
            }

            return $this->downloadPdf($invoice);
        }

        return AdminFeedback::success($request, 'Invoice generated successfully.', $this->returnDestination($request));
    }

    public function update(InvoiceRequest $request, Invoice $invoice): Response|JsonResponse|RedirectResponse
    {
        DB::transaction(function () use ($request, $invoice): void {
            $invoice->update($request->computedInvoiceData($invoice));
            $invoice->items()->delete();
            $items = $request->computedItemsData();
            if (!empty($items)) {
                $invoice->items()->createMany($items);
            }
        });

        $action = $request->input('submit_action', $request->input('action', 'save'));

        if ($action === 'export') {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Invoice updated! Downloading PDF…',
                    'redirect_url' => route('admin.invoices.pdf', $invoice),
                    'download_url' => route('admin.invoices.pdf', $invoice),
                    'return_url' => $this->returnDestination($request),
                ]);
            }

            return $this->downloadPdf($invoice);
        }

        return AdminFeedback::success($request, 'Invoice updated successfully.', $this->returnDestination($request));
    }

    public function destroy(Request $request, Invoice $invoice): JsonResponse|RedirectResponse
    {
        $invoice->delete();

        return AdminFeedback::success($request, 'Invoice deleted successfully.', $this->returnDestination($request));
    }

    public function downloadPdf(Invoice $invoice): Response
    {
        $invoice->load(['items.vehicle']);
        $settings = Setting::first();
        $logoBase64 = $this->getLogoBase64($settings);

        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice', 'settings', 'logoBase64'));

        $filename = Str::slug($invoice->invoice_number . '-' . $invoice->customer_name) . '.pdf';

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
