<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    protected $userRepository;
    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function login(Request $request){


        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);
        $user = $request->only('user_name', 'password');
        if ($validator->fails()) {

            $response = [
                'status' => 90,
                'errors' => $validator->errors()
            ];
            return $response;
        }


        $credentials = $request->only('user_name', 'password');
        $credentials['is_delete'] = 0;


        if (Auth::guard('web')->attempt($credentials)) {


            $user = Auth::guard('web')->user();
            //login app type=2

            if ($user->type == 2) {


                $user = Auth::guard('web')->user();

                $token_passport = $user->createToken('lotte-kitchen')->accessToken;

                $response = [
                    'status' => 200,
                    'token' => $token_passport,
                    'result' => $user
                ];

            } else {
                //nhấm type
                $response = [
                    'status' => 90,
                    'errors' => 'login fails'
                ];
            }
        } else {


            $response = [
                'status' => 90,
                'errors' => 'login fails'
            ];
        }

        return $response;

    }


    // Logout
//    public function logout()  {
//        auth()->user()->tokens()->delete();
//        $response = [
//            'message' => 'Logout Successfully'
//        ];
//        return response()->json($response,200);
//    }
}
