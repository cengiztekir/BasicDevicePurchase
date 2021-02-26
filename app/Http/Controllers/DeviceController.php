<?php
namespace App\Http\Controllers;

use App\Http\Requests\DeviceStoreRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Device;
use Validator;
use App\Http\Resources\Device as DeviceResource;
use App\Http\Repository\DeviceRepositoryInterface;

class DeviceController extends BaseController
{

    private $DeviceRepository;

    public function __construct(DeviceRepositoryInterface $DeviceRepository)
    {
        $this->DeviceRepository = $DeviceRepository;
    }

    public function index()
    {
        $Device = Device::all();

        return $this->sendResponse(DeviceResource::collection($Device), 'Device retrieved successfully.');
    }

    public function store(DeviceStoreRequest $request)
    {
        $request->validated();

        try {
            $RegisterControl = $this->DeviceRepository->find($request);

            $result = $this->DeviceRepository->create($request,$RegisterControl);

            return $this->sendResponse($result, 'Device has been created successfully.');

        } catch (Throwable $e) {
            return $this->sendError("System Error", ['error' => 'System Error']);
        }
    }

    public function show(Request $request)
    {
        try {
            $Device = $this->DeviceRepository->find($request);

            if (is_null($Device)) {
                return $this->sendError('Device not found.','',500);
            }

            return $this->sendResponse(new DeviceResource($Device), 'Device retrieved successfully.');

        } catch (Throwable $e) {
            return $this->sendError('Device not found.','');
        }
    }

    public function update(Request $request, Device $Device)
    {

    }

    public function destroy(Device $Device)
    {

    }
}

