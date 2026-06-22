<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->when($request->user(), $this->content),
            'excerpt' => \Illuminate\Support\Str::limit($this->content, 150),
            'views' => $this->views,
            'is_published' => (bool) $this->is_published,
            'published_at' => $this->published_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
            'author' => [
                'id' => $this->author->id,
                'name' => $this->author->name,
            ],
        ];
    }
}
