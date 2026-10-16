<?php

namespace App\Http\Controllers\Admin;

use App\Http\AdminFeedback;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVehicleRequest;
use App\Http\Requests\Admin\UpdateVehicleRequest;
use App\Http\Requests\Admin\VehicleRequest;
use App\Http\Requests\Admin\VehicleTableRequest;
use App\Models\Vehicle;
use App\Models\VehicleCapacity;
use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class VehicleController extends Controller
{
    private function columns(): array
    {
        return [
            ['data' => 'vehicle', 'label' => 'Vehicle', 'sort' => 'name', 'orderable' => true],
            ['data' => 'type', 'label' => 'Type', 'orderable' => false],
            ['data' => 'capacity', 'label' => 'Passenger capacity', 'orderable' => false],
            ['data' => 'pricing', 'label' => 'Pricing', 'sort' => 'price_per_km', 'orderable' => true],
            ['data' => 'visibility', 'label' => 'Visibility', 'orderable' => false],
            ['data' => 'sort_order', 'label' => 'Order', 'sort' => 'sort_order', 'orderable' => true],
            ['data' => 'actions', 'label' => 'Actions', 'orderable' => false],
        ];
    }

    private function choices(): array
    {
        return ['types' => VehicleType::orderBy('sort_order')->orderByDesc('id')->get(), 'capacities' => VehicleCapacity::orderBy('sort_order')->orderByDesc('id')->get()];
    }

    private function listingQuery(VehicleTableRequest $request): Builder
    {
        return Vehicle::query()->with(['type', 'capacity'])->when($request->searchTerm() !== '', function (Builder $query) use ($request): void {
            $search = '%'.$request->searchTerm().'%';
            $query->where(fn (Builder $query) => $query->where('name', 'like', $search)
                ->orWhereHas('type', fn (Builder $query) => $query->where('name', 'like', $search))
                ->orWhereHas('capacity', fn (Builder $query) => $query->where('label', 'like', $search)));
        })->when($request->input('status'), fn (Builder $query, string $status) => $query->where('is_published', $status === 'published'))
            ->when($request->input('vehicle_type_id'), fn (Builder $query, mixed $id) => $query->where('vehicle_type_id', $id))
            ->when($request->input('vehicle_capacity_id'), fn (Builder $query, mixed $id) => $query->where('vehicle_capacity_id', $id))
            ->orderBy($request->sortColumn(), $request->sortDirection())->orderByDesc('id');
    }

    public function index(VehicleTableRequest $request): View
    {
        return view('admin.vehicles.index', $this->choices() + ['columns' => $this->columns(), 'vehicles' => $this->listingQuery($request)->paginate($request->pageSize())->withQueryString()]);
    }

    public function data(VehicleTableRequest $request): JsonResponse
    {
        $query = $this->listingQuery($request);
        $count = (clone $query)->count();
        $columns = $this->columns();
        $rows = $query->offset($request->offset())->limit($request->pageSize())->get()->map(function (Vehicle $vehicle) use ($columns): array {
            $row = ['id' => $vehicle->id];
            foreach ($columns as $definition) {
                $column = $definition['data'];
                $row[$column] = view('admin.vehicles.cell', compact('vehicle', 'column'))->render();
            }

            return $row;
        });

        return response()->json(['draw' => (int) $request->input('draw', 0), 'recordsTotal' => Vehicle::count(), 'recordsFiltered' => $count, 'data' => $rows]);
    }

    private function returnDestination(Request $request): string
    {
        return AdminFeedback::returnDestination($request, 'admin.vehicles.index', [
            'q' => ['nullable', 'string', 'max:255'], 'status' => ['nullable', Rule::in(['published', 'draft'])],
            'vehicle_type_id' => ['nullable', 'integer', Rule::exists('vehicle_types', 'id')],
            'vehicle_capacity_id' => ['nullable', 'integer', Rule::exists('vehicle_capacities', 'id')],
            'sort' => ['sometimes', Rule::in(['name', 'price_per_km', 'sort_order'])], 'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
            'page' => ['sometimes', 'integer', 'min:1', 'max:21474836'], 'per_page' => ['sometimes', 'integer', Rule::in([15, 25, 50, 100])],
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.vehicles.form', $this->choices() + ['vehicle' => new Vehicle, 'returnTo' => $this->returnDestination($request)]);
    }

    public function edit(Request $request, Vehicle $vehicle): View
    {
        return view('admin.vehicles.form', $this->choices() + ['vehicle' => $vehicle, 'returnTo' => $this->returnDestination($request)]);
    }

    public function store(StoreVehicleRequest $request): JsonResponse|RedirectResponse
    {
        $this->persist($request, new Vehicle);

        return AdminFeedback::success($request, 'Vehicle created.', $this->returnDestination($request));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): JsonResponse|RedirectResponse
    {
        $this->persist($request, $vehicle);

        return AdminFeedback::success($request, 'Vehicle updated.', $this->returnDestination($request));
    }

    private function persist(VehicleRequest $request, Vehicle $vehicle): void
    {
        $newPath = null;
        $oldPath = null;
        try {
            if ($request->hasFile('image')) {
                $newPath = $request->file('image')->store('vehicles', 'public');
                if ($newPath === false) {
                    throw new RuntimeException('Unable to store the vehicle image.');
                }
            }
            for ($attempt = 0; $attempt < 3; $attempt++) {
                try {
                    $saved = DB::transaction(function () use ($request, $vehicle, $newPath, &$oldPath): Vehicle {
                        $record = $vehicle->exists ? Vehicle::lockForUpdate()->findOrFail($vehicle->id) : $vehicle;
                        $data = $request->vehicleData();
                        if ($request->generatedSlug()) {
                            $data['slug'] = Vehicle::uniqueSlug($request->slugBase());
                        }
                        $oldPath = $record->image_path;
                        if (is_string($newPath)) {
                            $data['image_path'] = $newPath;
                        } elseif ($request->boolean('remove_image')) {
                            $data['image_path'] = null;
                        }
                        if (! $record->fill($data)->save()) {
                            throw new RuntimeException('Unable to save the vehicle.');
                        }

                        return $record;
                    });
                    break;
                } catch (UniqueConstraintViolationException $exception) {
                    if (! $request->generatedSlug() || $attempt === 2) {
                        throw ValidationException::withMessages(['slug' => 'This URL slug is already in use. Please choose another or try again.']);
                    }
                }
            }
        } catch (Throwable $exception) {
            if (is_string($newPath)) {
                Storage::disk('public')->delete($newPath);
            }
            throw $exception;
        }
        if ($oldPath && $oldPath !== $saved->image_path) {
            Storage::disk('public')->delete($oldPath);
        }
    }

    public function destroy(Request $request, Vehicle $vehicle): JsonResponse|RedirectResponse
    {
        $path = DB::transaction(function () use ($vehicle): ?string {
            $record = Vehicle::lockForUpdate()->findOrFail($vehicle->id);
            $path = $record->image_path;
            if (! $record->delete()) {
                throw new RuntimeException('Unable to delete the vehicle.');
            }

            return $path;
        });
        if ($path) {
            Storage::disk('public')->delete($path);
        }

        return AdminFeedback::success($request, 'Vehicle deleted.', $this->returnDestination($request));
    }
}
