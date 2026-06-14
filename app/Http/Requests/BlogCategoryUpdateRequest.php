<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|min:5|max:200',
            'slug' => 'nullable|max:200',
            'description' => 'nullable|string|max:500|min:3',
            'parent_id' => 'required|integer|exists:blog_categories,id',
        ];
    }
}
