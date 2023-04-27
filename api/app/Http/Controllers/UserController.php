<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Http\Requests\User\UserForbidden;
use App\Http\Requests\User\UserGetAll;
use App\Http\Requests\User\UserRecover;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function getAll(UserGetAll $request) : JsonResponse{
       try{
           $query = User::when($request->input('filters.role'),function ($query,$role){
               $query->where('role',$role);
           })->when($request->input('filters.status'),function ($query,$status){
               $query->where('status',$status);
           })->when($request->input('query'),function ($query,$q){
               $query->where('user_name','like',"%$q%")
                   ->orWhere('first_name','like',"%$q%")
                   ->orWhere('last_name','like',"%$q%")
                   ->orWhereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ['%'.$q.'%'])
               ->orWhere('email','like',"%$q%");
           })->where('id','!=',auth()->user()->id);

           $paginator = $query->paginate($request->input('take',10));

           return response()->json([
               'success' => true,
               'message' => 'Users Successfully Listed',
               'data' => [
                   'users' => $paginator->items(),
                   'total' => $paginator->total()
               ]
           ]);
       }catch (\Throwable $th){
           return response()->json([
               'success' => false,
               'message' => $th->getMessage()
           ], 500);
       }
    }
    public function forbidden(User $user,UserForbidden $request) : JsonResponse {
        try{
             $user->update(['status' => UserStatus::FORBIDDEN]);

            return response()->json([
                'success' => true,
                'message' => 'User Has Been Successfully Forbidden',
            ]);
        }catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function recover(User $user,UserRecover $request) : JsonResponse {
        try{
            $user->update(['status' => UserStatus::ACTIVE]);

            return response()->json([
                'success' => true,
                'message' => 'User Has Been Successfully Recover',
            ]);
        }catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
