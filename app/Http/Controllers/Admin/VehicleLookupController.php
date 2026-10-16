<?php

namespace App\Http\Controllers\Admin;

use App\Http\AdminFeedback;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DataTableRequest;
use App\Http\Requests\Admin\VehicleLookupRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;

abstract class VehicleLookupController extends Controller
{
    /** @return array{model: class-string<Model>, prefix: string, title: string, singular: string, field: string, capacity: bool} */
    abstract protected function definition(): array;

    protected function columns(): array
    {
        $module = $this->definition();
        $columns = [['data' => $module['field'], 'label' => $module['capacity'] ? 'Display label' : 'Name', 'sort' => $module['field'], 'orderable' => true]];
        if ($module['capacity']) {
            $columns[] = ['data' => 'passenger_capacity', 'label' => 'Passengers (excluding driver)', 'sort' => 'passenger_capacity', 'orderable' => true];
        }

        return [...$columns,
            ['data' => 'vehicles_count', 'label' => 'Vehicles', 'orderable' => false],
            ['data' => 'sort_order', 'label' => 'Order', 'sort' => 'sort_order', 'orderable' => true],
            ['data' => 'actions', 'label' => 'Actions', 'orderable' => false],
        ];
    }

    protected function listingQuery(DataTableRequest $request): Builder
    {
        $module = $this->definition();

        return $module['model']::query()->withCount('vehicles')
            ->when($request->searchTerm() !== '', function (Builder $query) use ($request, $module): void {
                $query->where(function (Builder $query) use ($request, $module): void {
                    $query->where($module['field'], 'like', '%'.$request->searchTerm().'%');
                    if ($module['capacity'] && ctype_digit($request->searchTerm()) && (int) $request->searchTerm() <= 65535) {
                        $query->orWhere('passenger_capacity', (int) $request->searchTerm());
                    }
                });
            })->orderBy($request->sortColumn(), $request->sortDirection())->orderByDesc('id');
    }

    protected function indexResponse(DataTableRequest $request): View
    {
        return view('admin.vehicle-lookups.index', ['module' => $this->definition(), 'columns' => $this->columns(), 'records' => $this->listingQuery($request)->paginate($request->pageSize())->withQueryString()]);
    }

    protected function dataResponse(DataTableRequest $request): JsonResponse
    {
        $module = $this->definition();
        $query = $this->listingQuery($request);
        $count = (clone $query)->count();
        $columns = $this->columns();
        $rows = $query->offset($request->offset())->limit($request->pageSize())->get()->map(function (Model $record) use ($module, $columns): array {
            $row = ['id' => $record->id];
            foreach ($columns as $definition) {
                $column = $definition['data'];
                $row[$column] = view('admin.vehicle-lookups.cell', compact('module', 'record', 'column'))->render();
            }

            return $row;
        });

        return response()->json(['draw' => (int) $request->input('draw', 0), 'recordsTotal' => $module['model']::count(), 'recordsFiltered' => $count, 'data' => $rows]);
    }

    protected function returnDestination(Request $request): string
    {
        $module = $this->definition();
        $sorts = [$module['field'], 'sort_order'];
        if ($module['capacity']) {
            $sorts[] = 'passenger_capacity';
        }

        return AdminFeedback::returnDestination($request, $module['prefix'].'.index', [
            'q' => ['nullable', 'string', 'max:255'], 'sort' => ['sometimes', Rule::in($sorts)],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])], 'page' => ['sometimes', 'integer', 'min:1', 'max:21474836'],
            'per_page' => ['sometimes', 'integer', Rule::in([15, 25, 50, 100])],
        ]);
    }

    protected function formResponse(Request $request, Model $record): View
    {
        return view('admin.vehicle-lookups.form', ['module' => $this->definition(), 'record' => $record, 'returnTo' => $this->returnDestination($request)]);
    }

    protected function saveResponse(VehicleLookupRequest $request, Model $record): JsonResponse|RedirectResponse
    {
        $module = $this->definition();
        try {
            DB::transaction(function () use ($request, $record): void {
                if (! $record->fill($request->safe()->only(['name', 'label', 'passenger_capacity', 'sort_order']))->save()) {
                    throw new RuntimeException('Unable to save this record.');
                }
            });
        } catch (UniqueConstraintViolationException $exception) {
            throw ValidationException::withMessages([$module['field'] => 'This '.$module['field'].' is already in use.']);
        }

        return AdminFeedback::success($request, $module['singular'].' saved.', $this->returnDestination($request));
    }

    protected function deleteResponse(Request $request, Model $record): JsonResponse|RedirectResponse
    {
        $module = $this->definition();
        $blocked = false;
        try {
            $blocked = DB::transaction(function () use ($record): bool {
                $locked = $record->newQuery()->lockForUpdate()->findOrFail($record->id);
                if ($locked->vehicles()->exists()) {
                    return true;
                }
                if (! $locked->delete()) {
                    throw new RuntimeException('Unable to delete this record.');
                }

                return false;
            });
        } catch (QueryException $exception) {
            if (! in_array((int) ($exception->errorInfo[1] ?? 0), [1451, 787], true)
                && ! str_contains(strtolower($exception->getMessage()), 'foreign key constraint')) {
                throw $exception;
            }
            $blocked = true;
        }
        if ($blocked) {
            $message = 'This '.strtolower($module['singular']).' is used by one or more vehicles, including drafts. Reassign or delete those vehicles first.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 409);
            }

            return redirect()->to($this->returnDestination($request))->with('error', $message);
        }

        return AdminFeedback::success($request, $module['singular'].' deleted.', $this->returnDestination($request));
    }
}
