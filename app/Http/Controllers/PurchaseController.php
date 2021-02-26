<?php

namespace App\Http\Controllers;

use App\Http\Repository\PurchaseRepositoryInterface;
use App\Http\Repository\DeviceRepositoryInterface;
use App\Http\Requests\PurchaseStoreRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Purchase;
use App\Models\Device;
use Validator;
use App\Http\Resources\Purchase as PurchaseResource;

class PurchaseController extends BaseController
{
    /**
     * @var PurchaseRepositoryInterface
     */
    private $PurcahseRepository;
    /**
     * @var DeviceRepositoryInterface
     */
    private $DeviceRepository;

    /**
     * @var Carbon
     */
    private $now;

    /**
     * @var Device
     */
    private $Device;

    public function __construct(PurchaseRepositoryInterface $PurchaseRepository, DeviceRepositoryInterface $DeviceRepository,Carbon $now,Device $Device)
    {
        $this->PurcahseRepository = $PurchaseRepository;
        $this->DeviceRepository = $DeviceRepository;
        $this->now = Carbon::createFromFormat('Y-m-d H:i:s', now(-6))->format('Y-m-d H:i:s');
    }

    public function index()
    {
        $purchase = Purchase::all();

        return $this->sendResponse(PurchaseResource::collection($purchase), 'purchase retrieved successfully.');
    }

    public function store(PurchaseStoreRequest $request)
    {
        $ClientTokenName = "client-token";

        $PurchaseErrorRequest = new Request();
        $PurchaseErrorRequest->replace([
            'Status' => 0,
            'ExpireDate' => $this->now,
            'ClientToken' => $request->$ClientTokenName,
            'receipt' => $request->receipt,
            'Message' =>  null
        ]);

        $PurchaseSuccessRequest = new Request();
        $PurchaseSuccessRequest->replace([
            'Status' => null,
            'ExpireDate' => null,
            'ClientToken' => $request->$ClientTokenName,
            'receipt' => $request->receipt,
            'Message' =>  'Purchase has been created successfully.'
        ]);

        $request->validated();

        try {
            $this->Device = $this->DeviceRepository->find($request);

            if (is_null($this->Device)) {
                $PurchaseErrorRequest->merge([
                    'Message' =>  'Device not found.'
                ]);

                $purchase =$this->PurcahseRepository->create($PurchaseErrorRequest);

                return $this->sendError('Device not found.','',500);
            }

            $PurchaseControl = $this->PurcahseRepository->find($request);

            if (!is_null($PurchaseControl)){
                if($PurchaseControl->ExpireDate >= $this->now){
                    $PurchaseErrorRequest->merge([
                        'Message' =>  'Device has been have subscription'
                    ]);
                    $this->PurcahseRepository->create($PurchaseErrorRequest);

                    return $this->sendError('Device has been have subscription','',500);
                }
            }

            if(!in_array($this->Device->OpSys, ['Ios', 'Android'])){
                $PurchaseErrorRequest->merge([
                    'Message' =>  'Invalid Operating System'
                ]);
                $this->PurcahseRepository->create($PurchaseErrorRequest);

                return $this->sendError('Invalid Operating System','',500);

            }

            $response = Http::post('http://localhost:8001/api/'. $this->Device->OpSys .'MockApi', [
                'client-token' => $request->$ClientTokenName,
                'receipt' => $request->receipt,
            ]);

            $body = json_decode($response->body(), true);

            $status = ($body['data']['status']?1:0);
            $PurchaseSuccessRequest->merge([
                'Status' => $status,
                'ExpireDate' => $body['data']['expire-date'],
            ]);

            if($status!=1){
                $PurchaseSuccessRequest->merge([
                    'Message' =>  'Mock api denied purchase'
                ]);
            }

            $result = $this->PurcahseRepository->create($PurchaseSuccessRequest);

            return $this->sendResponse(($result), 'Purchase has been created successfully.');

        } catch (Throwable $e) {
            $PurchaseErrorRequest->merge([
                'Message' =>  'System Error'
            ]);
            $this->PurcahseRepository->create($PurchaseErrorRequest);

            return $this->sendError('System Error', ['error' => 'Page Error']);
        }
    }

    public function show(Request $request)
    {
        try {
            $Purchase = $this->PurcahseRepository->find($request);

            if (is_null($Purchase)) {
                return $this->sendError('Purchase not found.','',500);
            }

            return $this->sendResponse(new PurchaseResource($Purchase), 'Purchase retrieved successfully.');

        } catch (Throwable $e) {
            return $this->sendError('System Error', ['error' => 'Page Error']);
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
