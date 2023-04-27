<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AllBlogByAdminCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'status' => $this->status,
            'title' => $this->title,
            'image_url' => $this->image_url != '' ? url('uploads/blog/'.$this->image_url) : '',
            'like_count' => $this->likes_count,
            'comment_count' => $this->comments_count,
            'published_date' => $this->published_date,
            'created_at' => $this->created_at
        ];
    }
}
