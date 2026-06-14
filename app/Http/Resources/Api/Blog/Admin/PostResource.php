<?php

namespace App\Http\Resources\Api\Blog\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Перетворити пост у масив для API.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'title' => $this->title,

            'slug' => $this->slug,

            'excerpt' => $this->excerpt,

            'content_raw' => $this->content_raw,

            'content_html' => $this->content_html,

            'is_published' => (bool) $this->is_published,

            'published_at' => $this->published_at
                ? $this->published_at->format('Y-m-d H:i:s')
                : null,

            'date_published' => $this->published_at
                ? $this->published_at->format('Y-m-d H:i:s')
                : null,

            'user_id' => $this->user_id,

            'category_id' => $this->category_id,

            'author' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
            ],

            'category' => [
                'id' => $this->category?->id,
                'title' => $this->category?->title,
            ],
        ];
    }
}
