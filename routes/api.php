<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Blog\PostController as BlogPostController;
use App\Http\Controllers\Api\Blog\Admin\PostController as AdminPostController;
use App\Http\Controllers\Api\Blog\Admin\CategoryController;

Route::prefix('blog')->group(function () {
    Route::get('posts', [BlogPostController::class, 'index'])
        ->name('blog.posts.index');

    Route::get('posts/{id}', [BlogPostController::class, 'show'])
        ->name('blog.posts.show');
});

// Адмінка
$groupData = [
    'prefix' => 'admin/blog',
];

Route::group($groupData, function () {
    Route::get('categories-list', [CategoryController::class, 'list'])
        ->name('blog.admin.categories.list');

    Route::apiResource('categories', CategoryController::class)
        ->names('blog.admin.categories');

    Route::apiResource('posts', AdminPostController::class)
        ->names('blog.admin.posts');
});
