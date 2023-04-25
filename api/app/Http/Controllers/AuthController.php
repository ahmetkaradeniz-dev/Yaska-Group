<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request):JsonResponse{
        try{
            $user = User::create([
                'role' => UserRole::USER->value,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'user_name' => $request->user_name,
                'password' => $request->password
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully.',
                'data' => [
                    'token_type' => 'Bearer',
                    'access_token' => $user->createToken("API_TOKEN")->plainTextToken
                ]
            ]);

        }catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function login(LoginRequest $request):JsonResponse{
        try {

            if(!Auth::attempt($request->only(['user_name', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Username & Password Does Not Match With Our Record.',
                ], 401);
            }

            $user = User::where('user_name', $request->user_name)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'data' => [
                    'token_type' => 'Bearer',
                    'access_token' => $user->createToken("API_TOKEN")->plainTextToken
                ]
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request):JsonResponse{
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User Logged Out.',
        ]);
    }
}
