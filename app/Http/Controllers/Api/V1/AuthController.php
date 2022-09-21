<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Validator;

use App\Models\Opadmin;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admin-api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth('admin-api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:opadmins',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = Opadmin::create(array_merge(
                    $validator->validated(),
                    ['password' => Hash::make($request->password)]
                ));
        return response()->json([
            'message' => 'User successfully registered',
        ], 201);
    }

    public function currentUser() {
        return response()->json(auth('admin-api')->check());
    }

    public function logout() {
        auth('admin-api')->logout();
        return response()->json(['status' => true, 'message' => 'User successfully signed out']);
    }

    public function refresh() {
        return $this->createNewToken(auth('admin-api')->refresh());
    }

    public function userProfile() {
        return response()->json(auth('admin-api')->user());
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin-api')->factory()->getTTL() * 60,
            // 'user' => auth('admin-api')->user()
        ]);
    }
}
