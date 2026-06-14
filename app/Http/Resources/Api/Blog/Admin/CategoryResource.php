<?php

namespace App\Http\Resources\Api\Blog\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Перетворити категорію у масив для API.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'title' => $this->title,

            'parent_id' => $this->parent_id,

            'parent' => $this->parentCategory
                ? [
                    'id' => $this->parentCategory->id,
                    'title' => $this->parentCategory->title,
                ]
                : null,
        ];
    }
}
