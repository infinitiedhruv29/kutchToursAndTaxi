<?php

namespace App\Http\Controllers\Admin;

use App\Http\AdminFeedback;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InquiryTableRequest;
use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    private function columns(): array
    {
        return [
            ['data' => 'customer', 'label' => 'Customer', 'sort' => 'name', 'orderable' => true],
            ['data' => 'service', 'label' => 'Service / Requirement', 'orderable' => false],
            ['data' => 'status', 'label' => 'Status', 'sort' => 'status', 'orderable' => true],
            ['data' => 'date', 'label' => 'Inquiry Date', 'sort' => 'created_at', 'orderable' => true],
            ['data' => 'actions', 'label' => 'Actions', 'orderable' => false],
        ];
    }

    private function listingQuery(InquiryTableRequest $request): Builder
    {
        return Inquiry::query()
            ->when($request->searchTerm() !== '', function (Builder $query) use ($request): void {
                $search = '%' . $request->searchTerm() . '%';
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', $search)
                        ->orWhere('phone', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('service_title', 'like', $search)
                        ->orWhere('message', 'like', $search);
                });
            })
            ->when($request->input('status'), function (Builder $query, string $status): void {
                if (in_array($status, ['new', 'contacted', 'closed'])) {
                    $query->where('status', $status);
                }
            })
            ->when($request->input('service_type'), function (Builder $query, string $type): void {
                if ($type !== 'all') {
                    $query->where('service_type', $type);
                }
            })
            ->orderBy($request->sortColumn(), $request->sortDirection())
            ->orderByDesc('id');
    }

    public function index(InquiryTableRequest $request): View
    {
        $statusCounts = [
            'total' => Inquiry::count(),
            'new' => Inquiry::where('status', 'new')->count(),
            'contacted' => Inquiry::where('status', 'contacted')->count(),
            'closed' => Inquiry::where('status', 'closed')->count(),
        ];

        return view('admin.inquiries.index', [
            'columns' => $this->columns(),
            'inquiries' => $this->listingQuery($request)->paginate($request->pageSize())->withQueryString(),
            'statusCounts' => $statusCounts,
        ]);
    }

    public function data(InquiryTableRequest $request): JsonResponse
    {
        $query = $this->listingQuery($request);
        $count = (clone $query)->count();
        $columns = $this->columns();
        $rows = $query->offset($request->offset())->limit($request->pageSize())->get()->map(function (Inquiry $inquiry) use ($columns): array {
            $row = ['id' => $inquiry->id];
            foreach ($columns as $definition) {
                $column = $definition['data'];
                $row[$column] = view('admin.inquiries.cell', compact('inquiry', 'column'))->render();
            }

            return $row;
        });

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => Inquiry::count(),
            'recordsFiltered' => $count,
            'data' => $rows,
        ]);
    }

    public function show(Inquiry $inquiry): View
    {
        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:new,contacted,closed'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validated['status'] === 'contacted' && ! $inquiry->contacted_at) {
            $validated['contacted_at'] = now();
        }

        $inquiry->update($validated);

        return AdminFeedback::success($request, 'Inquiry updated successfully.', route('admin.inquiries.index'));
    }

    public function destroy(Request $request, Inquiry $inquiry): RedirectResponse|JsonResponse
    {
        $inquiry->delete();

        return AdminFeedback::success($request, 'Inquiry deleted successfully.', route('admin.inquiries.index'));
    }
}
