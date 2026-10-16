<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\VehicleCapacityRequest;
use App\Http\Requests\Admin\VehicleCapacityTableRequest;
use App\Models\VehicleCapacity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleCapacityController extends VehicleLookupController
{
    protected function definition(): array
    {
        return ['model' => VehicleCapacity::class, 'prefix' => 'admin.vehicle-capacities', 'title' => 'Passenger capacities', 'singular' => 'Passenger capacity', 'field' => 'label', 'capacity' => true];
    }

    public function index(VehicleCapacityTableRequest $request): View { return $this->indexResponse($request); }

    public function data(VehicleCapacityTableRequest $request): JsonResponse { return $this->dataResponse($request); }

    public function create(Request $request): View { return $this->formResponse($request, new VehicleCapacity); }

    public function edit(Request $request, VehicleCapacity $vehicleCapacity): View { return $this->formResponse($request, $vehicleCapacity); }

    public function store(VehicleCapacityRequest $request): JsonResponse|RedirectResponse { return $this->saveResponse($request, new VehicleCapacity); }

    public function update(VehicleCapacityRequest $request, VehicleCapacity $vehicleCapacity): JsonResponse|RedirectResponse { return $this->saveResponse($request, $vehicleCapacity); }

    public function destroy(Request $request, VehicleCapacity $vehicleCapacity): JsonResponse|RedirectResponse { return $this->deleteResponse($request, $vehicleCapacity); }
}
