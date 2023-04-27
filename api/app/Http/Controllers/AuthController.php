<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthCollection;
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
                'success' => true,
                'message' => 'Successfully Registered',
                'data' => [
                    'user' => new AuthCollection($user),
                    'token' => [
                        'type' => 'Bearer',
                        'access' => $user->createToken("API_TOKEN")->plainTextToken
                    ]
                ]
            ]);
        }catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function login(LoginRequest $request):JsonResponse{
        try {
            if(!Auth::attempt($request->only(['user_name', 'password']))){
                return response()->json([
                    'success' => false,
                    'message' => 'Username & Password Does Not Match With Our Record',
                ]);
            }

            $user = User::where('user_name', $request->user_name)->first();

            if($user->status == UserStatus::FORBIDDEN) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your Account Has Been Banned',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully Logged In',
                'data' => [
                    'user' => new AuthCollection($user),
                    'token' => [
                        'type' => 'Bearer',
                        'access' => $user->createToken("API_TOKEN")->plainTextToken
                    ]
                ]
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request):JsonResponse{
        try {
            auth()->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'User Has Been Successfully Logout',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }

    }
}
