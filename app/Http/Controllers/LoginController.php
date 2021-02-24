<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;

class LoginController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            try {
                $user = Auth::user();
                $result['token'] = $user->createToken('MyApp')->accessToken;
                $result['name'] = $user->name;
                $result['code'] = 200;
                return $this->sendResponse($result, 'User login successfully.');
            } catch (Throwable $e) {
                $result['code'] = 500;
                return $this->sendError($result, ['error' => 'Page Error']);
            }
        } else {
            $result['code'] = 401;
            return $this->sendError($result, ['error' => 'Unauthorised']);
        }
    }
}
