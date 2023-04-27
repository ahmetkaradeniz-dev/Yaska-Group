<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\BlogStatus;

class BlogLike extends Model
{
    use HasFactory;

    protected $table = 'blog_likes';

    protected $fillable = [
        'blog_id',
        'user_id',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
