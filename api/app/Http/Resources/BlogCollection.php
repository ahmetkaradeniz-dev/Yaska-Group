<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class BlogCollection extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'author' => $this->user->first_name.' '.$this->user->last_name,
            'image_url' => $this->image_url != '' ? url('uploads/blog/'.$this->image_url) : '',
            'like_count' => $this->likes_count,
            'comment_count' => $this->comments_count,
            'created_at' => $this->created_at
        ];
    }
}
