<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Http\Resources\AllBlogCollection;
use App\Models\Blog;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AccountController extends Controller
{
    public function blog(Request $request){
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
