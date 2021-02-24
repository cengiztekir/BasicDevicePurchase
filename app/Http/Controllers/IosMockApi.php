<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class IosMockApi extends BaseController
{
    public function CheckPurchase(Request $request)
    {
        $rules = [
            'client-token' => 'required|string',
            'receipt' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError( 'Validation Error.',$validator->errors(),500);
        }

        try {

            $date = Carbon::createFromFormat('Y-m-d H:i:s', now(-6)->addMonths(5))->format('Y-m-d H:i:s');

            if($request->receipt%2==1){
                $result['status'] = true;
                $result['expire-date'] = $date;
                return $this->sendResponse( $result,'');
            }else{
                $result['status'] = false;
                $result['expire-date'] = $date;
                return $this->sendResponse( $result,'');
            }
        } catch (Throwable $e) {
            return $this->sendError("System Error", ['error' => 'Page Error']);
        }
    }
}
