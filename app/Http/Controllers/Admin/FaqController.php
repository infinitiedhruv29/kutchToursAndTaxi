<?php

namespace App\Http\Controllers\Admin;

use App\Http\AdminFeedback;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Http\Requests\Admin\FaqTableRequest;
use App\Models\Faq;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class FaqController extends Controller
{
    private function columns(): array
    {
        return [
            ['data' => 'question', 'label' => 'Question', 'sort' => 'question', 'orderable' => true],
            ['data' => 'answer', 'label' => 'Answer', 'orderable' => false],
            ['data' => 'visibility', 'label' => 'Visibility', 'orderable' => false],
            ['data' => 'sort_order', 'label' => 'Order', 'sort' => 'sort_order', 'orderable' => true],
            ['data' => 'actions', 'label' => 'Actions', 'orderable' => false],
        ];
    }

    private function listingQuery(FaqTableRequest $request): Builder
    {
        return Faq::query()->when($request->searchTerm() !== '', fn (Builder $query) => $query->where('question', 'like', '%'.$request->searchTerm().'%'))
            ->when($request->input('status'), fn (Builder $query, string $status) => $query->where('is_published', $status === 'published'))
            ->orderBy($request->sortColumn(), $request->sortDirection())->orderByDesc('id');
    }

    public function index(FaqTableRequest $request): View
    {
        return view('admin.faqs.index', ['columns' => $this->columns(), 'faqs' => $this->listingQuery($request)->paginate($request->pageSize())->withQueryString()]);
    }

    public function data(FaqTableRequest $request): JsonResponse
    {
        $query = $this->listingQuery($request);
        $count = (clone $query)->count();
        $columns = $this->columns();
        $rows = $query->offset($request->offset())->limit($request->pageSize())->get()->map(function (Faq $faq) use ($columns): array {
            $row = ['id' => $faq->id];
            foreach ($columns as $definition) {
                $column = $definition['data'];
                $row[$column] = view('admin.faqs.cell', compact('faq', 'column'))->render();
            }

            return $row;
        });

        return response()->json(['draw' => (int) $request->input('draw', 0), 'recordsTotal' => Faq::count(), 'recordsFiltered' => $count, 'data' => $rows]);
    }

    private function returnDestination(Request $request): string
    {
        return AdminFeedback::returnDestination($request, 'admin.faqs.index', [
            'q' => ['nullable', 'string', 'max:255'], 'status' => ['nullable', Rule::in(['published', 'draft'])],
            'sort' => ['sometimes', Rule::in(['question', 'sort_order'])], 'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
            'page' => ['sometimes', 'integer', 'min:1', 'max:21474836'], 'per_page' => ['sometimes', 'integer', Rule::in([15, 25, 50, 100])],
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.faqs.form', ['faq' => new Faq, 'returnTo' => $this->returnDestination($request)]);
    }

    public function edit(Request $request, Faq $faq): View
    {
        return view('admin.faqs.form', ['faq' => $faq, 'returnTo' => $this->returnDestination($request)]);
    }

    public function store(FaqRequest $request): JsonResponse|RedirectResponse
    {
        $this->persist($request, new Faq);

        return AdminFeedback::success($request, 'FAQ created.', $this->returnDestination($request));
    }

    public function update(FaqRequest $request, Faq $faq): JsonResponse|RedirectResponse
    {
        $this->persist($request, $faq);

        return AdminFeedback::success($request, 'FAQ updated.', $this->returnDestination($request));
    }

    private function persist(FaqRequest $request, Faq $faq): void
    {
        DB::transaction(function () use ($request, $faq): void {
            $record = $faq->exists ? Faq::lockForUpdate()->findOrFail($faq->id) : $faq;
            if (! $record->fill($request->contentData())->save()) {
                throw new RuntimeException('Unable to save the FAQ.');
            }
        });
    }

    public function destroy(Request $request, Faq $faq): JsonResponse|RedirectResponse
    {
        DB::transaction(function () use ($faq): void {
            if (! Faq::lockForUpdate()->findOrFail($faq->id)->delete()) {
                throw new RuntimeException('Unable to delete the FAQ.');
            }
        });

        return AdminFeedback::success($request, 'FAQ deleted.', $this->returnDestination($request));
    }
}
