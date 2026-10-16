<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\VehicleTypeRequest;
use App\Http\Requests\Admin\VehicleTypeTableRequest;
use App\Models\VehicleType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleTypeController extends VehicleLookupController
{
    protected function definition(): array
    {
        return ['model' => VehicleType::class, 'prefix' => 'admin.vehicle-types', 'title' => 'Vehicle types', 'singular' => 'Vehicle type', 'field' => 'name', 'capacity' => false];
    }

    public function index(VehicleTypeTableRequest $request): View { return $this->indexResponse($request); }

    public function data(VehicleTypeTableRequest $request): JsonResponse { return $this->dataResponse($request); }

    public function create(Request $request): View { return $this->formResponse($request, new VehicleType); }

    public function edit(Request $request, VehicleType $vehicleType): View { return $this->formResponse($request, $vehicleType); }

    public function store(VehicleTypeRequest $request): JsonResponse|RedirectResponse { return $this->saveResponse($request, new VehicleType); }

    public function update(VehicleTypeRequest $request, VehicleType $vehicleType): JsonResponse|RedirectResponse { return $this->saveResponse($request, $vehicleType); }

    public function destroy(Request $request, VehicleType $vehicleType): JsonResponse|RedirectResponse { return $this->deleteResponse($request, $vehicleType); }
}
