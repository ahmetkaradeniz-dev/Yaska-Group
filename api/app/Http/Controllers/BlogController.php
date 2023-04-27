<?php

namespace App\Http\Controllers;

use App\Enums\BlogStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Requests\Blog\BlogCommentAllRequest;
use App\Http\Requests\Blog\BlogCommentDeletedRequest;
use App\Http\Requests\Blog\BlogCommentRequest;
use App\Http\Requests\Blog\BlogCommentUpdateRequest;
use App\Http\Requests\Blog\BlogCreateRequest;
use App\Http\Requests\Blog\BlogDeleteRequest;
use App\Http\Requests\Blog\BlogLikeRequest;
use App\Http\Requests\Blog\BlogRequest;
use App\Http\Requests\Blog\BlogUnLikeRequest;
use App\Http\Requests\Blog\BlogUpdateRequest;
use App\Http\Resources\AllBlogByAdminCollection;
use App\Http\Resources\AllBlogCollection;
use App\Http\Resources\AllBlogCommentByAdminCollection;
use App\Http\Resources\AllBlogCommentCollection;
use App\Http\Resources\BlogCollection;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\BlogLike;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    public function getAll(Request $request):JsonResponse{
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
    public function getAllByAdmin(Request $request):JsonResponse{
        try{
            $query = Blog::with(['user'])
                ->withCount(['likes','comments'])
                    ->when($request->input('filters.user_id'),function ($query,$user_id){
                        $query->where('user_id',$user_id);
                    })->when($request->input('filters.status'),function ($query,$status){
                        $query->where('status',$status);
                    })->when($request->input('filters.published_date'),function ($query,$published_date){
                        $dates = explode('to',$published_date);
                        if(isset($dates[1])){
                            $query->where('published_date', '>=', $dates[0].' 00:00:00')->where('published_date','<=',$dates[1].' 23:59:59');
                        }else{
                            $query->where('published_date', '>=', $dates[0].' 00:00:00')->where('published_date','<=',$dates[0].' 23:59:59');
                        }
                    })->when($request->input('query'),function ($query,$q){
                        $query->where('title','like',"%$q%")
                    ->orWhere('content','like',"%$q%");
                    });

            $paginator = $query->paginate($request->input('take',10));

            return response()->json([
                'success' => true,
                'message' => 'Users Successfully Listed',
                'data' => [
                    'blog' => AllBlogByAdminCollection::collection( $paginator->items()),
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
    public function get(Blog $blog,BlogRequest $request):JsonResponse{
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
    public function getByAdmin(Blog $blog,Request $request) : JsonResponse{
        try {
            $query = Blog::where('id','=',$blog->id);

            return response()->json([
                'success' => true,
                'message' => 'Blog Successfully Get',
                'data' => [
                    'blog' => $query->first()
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
    public function update(Blog $blog,BlogUpdateRequest $request) : JsonResponse {
        try {
            if(auth()->user()->role == UserRole::USER && auth()->user()->id != $blog->user_id){
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ]);
            }

            if($request->hasFile('file')){
                if($blog->image_url){
                    $path = 'uploads/blog/'.$blog->image_url;
                    if (File::exists(public_path($path))) {
                        File::delete(public_path($path));
                    }
                }

                $file = $request->file('file');
                $fileBody = hash('crc32', uniqid());
                $fileName = $fileBody . '.' . Str::lower($file->getClientOriginalExtension());
                $filePath = 'uploads/blog/';
                $file->move($filePath,$fileName);
            }

            $blog->update([
                'status' => $request->input('status'),
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
    public function delete(Blog $blog,BlogDeleteRequest $request):JsonResponse{
        try {
            if(auth()->user()->role == UserRole::USER && auth()->user()->id != $blog->user_id){
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ]);
            }

            $image_url = $blog->image_url;

            if($blog->delete()){
                 if($image_url) {
                     $path = 'uploads/blog/'.$blog->image_url;
                      if (File::exists(public_path($path))) {
                          File::delete(public_path($path));
                      }
                 }
            }

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
    public function unLike(Blog $blog,BlogUnLikeRequest $request):JsonResponse{
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
    public function comments(Blog $blog,BlogCommentAllRequest $request):JsonResponse{
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
    public function getAllCommentByAdmin(Request $request) : JsonResponse{
        try{
            $query = BlogComment::with(['blog','blog.user'])
                ->when($request->input('filters.blog_id'),function ($query,$blog_id){
                $query->where('blog_comments.blog_id','=',$blog_id);
            })->when($request->input('filters.user_id'),function ($query,$user_id){
                $query->where('user_id',$user_id);
            })->when($request->input('query'),function ($query,$q){
                $query->where('comment','like',"%$q%");
            });

            $paginator = $query->paginate($request->input('take',10));

            return response()->json([
                'success' => true,
                'message' => 'Blog Comment Successfully Listed',
                'data' => [
                    'comments' => AllBlogCommentByAdminCollection::collection($paginator->items()),
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
    public function getCommentByAdmin(BlogComment $blogComment) : JsonResponse{
        try {
            return response()->json([
                'success' => true,
                'message' => 'Blog Comment Successfully Get',
                'data' => [
                    'comment' =>  $blogComment,
                ]
            ]);
        }catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function updateCommentByAdmin(BlogComment $blogComment,BlogCommentUpdateRequest $request) : JsonResponse {
        try {
            $blogComment->update([
                'comment' => $request->comment
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Blog Comment Updated Successfully.',
            ]);
        }catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function deleteCommentByAdmin(BlogComment $blogComment,BlogCommentDeletedRequest $request):JsonResponse{
        try {
            $blogComment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Blog Comment Has Been Successfully Deleted',
            ]);
        }catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
