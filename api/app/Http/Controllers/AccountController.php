<?php

namespace App\Http\Controllers;

use App\Http\Resources\AllBlogCollection;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function blog(Request $request) : JsonResponse{
        try{
            $query = Blog::withCount(['likes','comments'])
                ->where('user_id','=',auth()->user()->id)
                ->orderBy($request->input('column','created_at'),$request->input('direction','DESC'));

            $paginator = $query->paginate($request->input('take',10));

            return response()->json([
                'success' => true,
                'message' => 'Blog Successfully Listed',
                'data' => [
                    'blog' => AllBlogCollection::collection($paginator->items()),
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
}
