<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Purchase;
use Validator;
use App\Http\Resources\Purchase as PurchaseResource;
use App\Models\Device;

class PurchaseController extends BaseController
{
    public function index()
    {
        $device = Purchase::all();

        return $this->sendResponse(PurchaseResource::collection($device), 'purchase retrieved successfully.');
    }

    public function store(Request $request)
    {
        $ClientTokenName = "client-token";

        $rules = [
            'client-token' => 'required|string',
            'receipt' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $purchase = new Purchase([
                'Status' => 0,
                'ExpireDate' => Carbon::now(),
                'ClientToken' => $request->$ClientTokenName,
                'receipt' => $request->receipt,
                'Message' =>  'Validation Error'
            ]);

            $purchase->save();

            return $this->sendError( 'Validation Error.',$validator->errors(),500);
        }

        try {

            $Device = Device::where('ClientToken', '=', $request->$ClientTokenName)->first();

            if (is_null($Device)) {
                $purchase = new Purchase([
                    'Status' => 0,
                    'ExpireDate' => Carbon::now(),
                    'ClientToken' => $request->$ClientTokenName,
                    'receipt' => $request->receipt,
                    'Message' =>  'Device not found.'
                ]);

                $purchase->save();

                return $this->sendError('Device not found.','',500);
            }

            $PurchaseControl = $this->show($request->$ClientTokenName);

            if($PurchaseControl->original['success']==true){
                $now = Carbon::createFromFormat('Y-m-d H:i:s', now(-6))->format('Y-m-d H:i:s');
                if($PurchaseControl->original['data']['ExpireDate']>=$now){
                    $purchase = new Purchase([
                        'Status' => 0,
                        'ExpireDate' => Carbon::now(),
                        'ClientToken' => $request->$ClientTokenName,
                        'receipt' => $request->receipt,
                        'Message' =>  'Device has been have subscription'
                    ]);

                    $purchase->save();
                    return $this->sendError('Device has been have subscription','',500);
                }
            }

            if($Device->OpSys=='Ios'){
                $response = Http::post('http://localhost:8001/api/IosMockApi', [
                    'client-token' => $request->$ClientTokenName,
                    'receipt' => $request->receipt,
                ]);
            } else if ($Device->OpSys=='Android') {
                $response = Http::post('http://localhost:8001/api/AndroidMockApi', [
                    'client-token' => $request->$ClientTokenName,
                    'receipt' => $request->receipt,
                ]);
            }else {
                $purchase = new Purchase([
                    'Status' => 0,
                    'ExpireDate' => Carbon::now(),
                    'ClientToken' => $request->$ClientTokenName,
                    'receipt' => $request->receipt,
                    'Message' =>  'Invalid Operating System'
                ]);

                $purchase->save();
                return $this->sendError('Invalid Operating System','',500);
            }

            $body = json_decode($response->body(), true);

            $status = ($body['data']['status']?1:0);

            if($status==1){
                $purchase = new Purchase([
                    'Status' => $status,
                    'ExpireDate' => $body['data']['expire-date'],
                    'ClientToken' => $request->$ClientTokenName,
                    'receipt' => $request->receipt,
                    'Message' =>  'Purchase has been created successfully.'
                ]);

                $purchase->save();

                return $this->sendResponse(($purchase), 'Purchase has been created successfully.');
            } else {
                $purchase = new Purchase([
                    'Status' => $status,
                    'ExpireDate' => $body['data']['expire-date'],
                    'ClientToken' => $request->$ClientTokenName,
                    'receipt' => $request->receipt,
                    'Message' =>  'Mock api denied purchase'
                ]);

                $purchase->save();

                return $this->sendResponse(($purchase), 'Mock api denied purchase');
            }

        } catch (Throwable $e) {
            $purchase = new Purchase([
                'Status' => $status,
                'ExpireDate' => $body['data']['expire-date'],
                'ClientToken' => $request->$ClientTokenName,
                'receipt' => $request->receipt,
                'Message' =>  'System Error'
            ]);

            $purchase->save();
            return $this->sendError('System Error', ['error' => 'Page Error']);
        }
    }

    public function show($ClientToken)
    {
        try {
            $Purchase = Purchase::where('ClientToken', '=', $ClientToken)->where('Status','=','1')->last();

            if (is_null($Purchase)) {
                return $this->sendError('Purchase not found.','',500);
            }

            return $this->sendResponse(new PurchaseResource($Purchase), 'Purchase retrieved successfully.');

        } catch (Throwable $e) {
            return $this->sendError('Purchase not found.','',500);
        }
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
