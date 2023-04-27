<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Http\Requests\User\UserForbidden;
use App\Http\Requests\User\UserGetAll;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function getAll(UserGetAll $request) : JsonResponse{
       try{
           $query = User::when($request->input('role'),function ($query,$role){
               $query->where('role',$role);
           })->when($request->input('status'),function ($query,$status){
               $query->where('status',$status);
           });

           $paginator = $query->paginate($request->input('take',10));

           return response()->json([
               'status' => true,
               'message' => 'Users Successfully Listed',
               'data' => [
                   'users' => $paginator->items(),
                   'total' => $paginator->total()
               ]
           ]);
       }catch (\Throwable $th){
           return response()->json([
               'status' => false,
               'message' => $th->getMessage()
           ], 500);
       }
    }
    public function forbidden(User $user,UserForbidden $request) : JsonResponse {
        try{
             $user->update(['status' => UserStatus::FORBIDDEN]);

            return response()->json([
                'status' => true,
                'message' => 'User Has Been Successfully Forbidden',
            ]);
        }catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
