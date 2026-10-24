<?php

namespace App\Http\Controllers\Admin;

use App\Http\AdminFeedback;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Http\Requests\Admin\TestimonialTableRequest;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    private function columns(): array
    {
        return [
            ['data' => 'client', 'label' => 'Guest / Client', 'sort' => 'client_name', 'orderable' => true],
            ['data' => 'service', 'label' => 'Tour / Service', 'sort' => 'service_name', 'orderable' => true],
            ['data' => 'review', 'label' => 'Review snippet', 'orderable' => false],
            ['data' => 'status', 'label' => 'Status', 'sort' => 'is_published', 'orderable' => true],
            ['data' => 'sort_order', 'label' => 'Order', 'sort' => 'sort_order', 'orderable' => true],
            ['data' => 'actions', 'label' => 'Actions', 'orderable' => false],
        ];
    }

    private function listingQuery(TestimonialTableRequest $request): Builder
    {
        return Testimonial::query()
            ->when($request->searchTerm() !== '', function (Builder $query) use ($request): void {
                $search = '%' . $request->searchTerm() . '%';
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('client_name', 'like', $search)
                        ->orWhere('service_name', 'like', $search)
                        ->orWhere('review', 'like', $search);
                });
            })
            ->when($request->input('status'), function (Builder $query, string $status): void {
                if ($status === 'published') {
                    $query->where('is_published', true);
                } elseif ($status === 'draft') {
                    $query->where('is_published', false);
                }
            })
            ->orderBy($request->sortColumn(), $request->sortDirection())
            ->orderByDesc('id');
    }

    public function index(TestimonialTableRequest $request): View
    {
        return view('admin.testimonials.index', [
            'columns' => $this->columns(),
            'testimonials' => $this->listingQuery($request)->paginate($request->pageSize())->withQueryString(),
        ]);
    }

    public function data(TestimonialTableRequest $request): JsonResponse
    {
        $query = $this->listingQuery($request);
        $count = (clone $query)->count();
        $columns = $this->columns();
        $rows = $query->offset($request->offset())->limit($request->pageSize())->get()->map(function (Testimonial $testimonial) use ($columns): array {
            $row = ['id' => $testimonial->id];
            foreach ($columns as $definition) {
                $column = $definition['data'];
                $row[$column] = view('admin.testimonials.cell', compact('testimonial', 'column'))->render();
            }

            return $row;
        });

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => Testimonial::count(),
            'recordsFiltered' => $count,
            'data' => $rows,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.testimonials.form', [
            'testimonial' => new Testimonial(['sort_order' => 0, 'is_published' => true]),
            'returnTo' => $request->input('return_to', route('admin.testimonials.index')),
        ]);
    }

    public function store(TestimonialRequest $request): RedirectResponse|JsonResponse
    {
        $testimonial = Testimonial::create($request->testimonialData());

        return AdminFeedback::success($request, 'Testimonial added successfully.', route('admin.testimonials.index'));
    }

    public function edit(Request $request, Testimonial $testimonial): View
    {
        return view('admin.testimonials.form', [
            'testimonial' => $testimonial,
            'returnTo' => $request->input('return_to', route('admin.testimonials.index')),
        ]);
    }

    public function update(TestimonialRequest $request, Testimonial $testimonial): RedirectResponse|JsonResponse
    {
        $testimonial->update($request->testimonialData());

        return AdminFeedback::success($request, 'Testimonial updated successfully.', route('admin.testimonials.index'));
    }

    public function destroy(Request $request, Testimonial $testimonial): RedirectResponse|JsonResponse
    {
        $testimonial->delete();

        return AdminFeedback::success($request, 'Testimonial deleted successfully.', route('admin.testimonials.index'));
    }
}
