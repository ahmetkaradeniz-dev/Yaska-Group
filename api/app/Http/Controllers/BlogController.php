<?php

namespace App\Http\Controllers;

use App\Enums\BlogStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Enums\Usersuccess;
use App\Http\Requests\Blog\BlogCommentAllRequest;
use App\Http\Requests\Blog\BlogCommentRequest;
use App\Http\Requests\Blog\BlogCreateRequest;
use App\Http\Requests\Blog\BlogDeleteRequest;
use App\Http\Requests\Blog\BlogLikeRequest;
use App\Http\Requests\Blog\BlogRequest;
use App\Http\Requests\Blog\BlogUnLikeRequest;
use App\Http\Requests\Blog\BlogUpdateRequest;
use App\Http\Resources\AllBlogCollection;
use App\Http\Resources\AllBlogCommentCollection;
use App\Http\Resources\BlogCollection;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\BlogLike;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function getAll(Request $request){
        try{
            $query = Blog::withCount(['likes','comments'])
                ->with(['user'])
                ->join('users', function (JoinClause $join) {
                    $join->on('blog.user_id', '=', 'users.id')
                        ->where('users.status', '=', UserStatus::ACTIVE);
                })
                ->where('blog.published_date','<=',Carbon::now()->toDateTimeString())
                ->where('blog.status','=',BlogStatus::ACTIVE)
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

    public function get(Blog $blog,BlogRequest $request){
        try {
            $query = Blog::where('id','=',$blog->id)
                ->where('status','=',BlogStatus::ACTIVE)
                ->where('published_date','<=',Carbon::now()->toDateTimeString())
                ->withCount(['likes','comments'])
                ->with(['user']);

            $is_liked = BlogLike::where([
                'blog_id' => $blog->id,
                'user_id' => auth()->user()->id
            ])->first();

            return response()->json([
                'success' => true,
                'message' => 'Blog Successfully Get',
                'data' => [
                    'blog' => new BlogCollection($query->first()),
                    'is_liked' => (bool)$is_liked
                ]
            ]);
        }catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function create(BlogCreateRequest $request) : JsonResponse{
        try{
            if($request->hasFile('file')){
                $file = $request->file('file');
                $fileBody = hash('crc32', uniqid());
                $fileName = $fileBody . '.' . Str::lower($file->getClientOriginalExtension());
                $filePath = 'uploads/blog/';
                $file->move($filePath,$fileName);
            }

            Blog::create([
                'user_id' => auth()->user()->id,
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'published_date' => $request->input('published_date') ?? Carbon::now()->toDateTimeString(),
                'image_url' => $fileName ?? null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Blog Created Successfully.',
            ]);
        }catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function update(Blog $blog,BlogUpdateRequest $request){
        try {
            if(auth()->user()->role == UserRole::USER && auth()->user()->id != $blog->user_id){
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ],403);
            }

            if($request->hasFile('file')){
                if($blog->image_url){
                    Storage::delete('uploads/blog/'.$blog->image_url);
                }

                $file = $request->file('file');
                $fileBody = hash('crc32', uniqid());
                $fileName = $fileBody . '.' . Str::lower($file->getClientOriginalExtension());
                $filePath = 'uploads/blog/';
                $file->move($filePath,$fileName);
            }

            $blog->update([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'published_date' => $request->input('published_date') ?? Carbon::now()->toDateTimeString(),
                'image_url' => $fileName ?? null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Blog Updated Successfully.',
            ]);

        }catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function delete(Blog $blog,BlogDeleteRequest $request){
        try {
            if(auth()->user()->role == UserRole::USER && auth()->user()->id != $blog->user_id){
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ],403);
            }

            $blog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Blog Has Been Successfully Deleted',
            ]);

        }catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function like(Blog $blog,BlogLikeRequest $request) : JsonResponse{
        try {
            BlogLike::upsert([
                'blog_id' => $blog->id,
                'user_id' => auth()->user()->id
            ],['blog_id','user_id']);

            return response()->json([
                'success' => true,
                'message' => 'Blog Has Been Successfully Liked',
            ]);
        }catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function unLike(Blog $blog,BlogUnLikeRequest $request){
        try {
            BlogLike::deleted([
                'blog_id' => $blog->id,
                'user_id' => auth()->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Blog Has Been Successfully Unlinked',
            ]);
        }catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function comments(Blog $blog,BlogCommentAllRequest $request){
        try{
            $query = BlogComment::join('blog', function (JoinClause $join) {
                    $join->on('blog_comments.blog_id', '=', 'blog.id')
                        ->where('blog.status','=',BlogStatus::ACTIVE)
                        ->where('blog.published_date','<=',Carbon::now()->toDateTimeString());
            })->join('users',function (JoinClause $join){
              $join->on('blog_comments.user_id','=','users.id');
            })->where('blog_comments.blog_id','=',$blog->id);

            $paginator = $query->paginate($request->input('take',10));

            return response()->json([
                'success' => true,
                'message' => 'Blog Comment Successfully Listed',
                'data' => [
                    'comments' => AllBlogCommentCollection::collection($paginator->items()),
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
    public function comment(Blog $blog,BlogCommentRequest $request) : JsonResponse{
        try {
            BlogComment::create([
                'blog_id' => $blog->id,
                'user_id' => auth()->user()->id,
                'comment' => $request->input('comment')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comment Has Been Successfully Created',
            ]);
        }catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
