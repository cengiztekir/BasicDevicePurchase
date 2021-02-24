<?php
namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Device;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Http\Resources\Device as DeviceResource;


class DeviceController extends BaseController
{

    public function index()
    {
        $device = Device::all();

        return $this->sendResponse(DeviceResource::collection($device), 'device retrieved successfully.');
    }

    public function store(Request $request)
    {
        $rules = [
            'uid' => 'required|string',
            'appId' => 'required|string',
            'language' => 'required|string',
            'OpSys' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError( 'Validation Error.',$validator->errors(),500);
        }

        try {
            $RegisterControl = $this->show($request);

            if($RegisterControl->original['success']==true){
                if($RegisterControl->original['data']['appId']==$request->appId){
                    $result['client-token'] = $RegisterControl->original['data']['ClientToken'];
                    return $this->sendResponse($result, 'Device has been created successfully.');
                }
            }

            $ClientToken = str_random(60);

            $device = new device([
                'uid' => $request->uid,
                'appId' => $request->appId,
                'language' => $request->language,
                'OpSys' => $request->OpSys,
                'ClientToken' => $ClientToken
            ]);

            $device->save();
            $result['client-token'] = $ClientToken;
            return $this->sendResponse($result, 'Device has been created successfully.');

        } catch (Throwable $e) {
            return $this->sendError("System Error", ['error' => 'System Error']);
        }
    }

    public function show(Request $request)
    {
        try {
            $Device = Device::where('uid', '=', $request->uid)->where('appId','=',$request->appId)->first();

            if (is_null($Device)) {
                return $this->sendError('Device not found.','',500);
            }

            return $this->sendResponse(new DeviceResource($Device), 'Device retrieved successfully.');

        } catch (Throwable $e) {
            return $this->sendError('Device not found.','');
        }
    }

    public function update(Request $request, Device $device)
    {

    }

    public function destroy(Device $device)
    {

    }
}

