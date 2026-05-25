<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Blog\PostController;

use App\Http\Controllers\Api\Blog\Admin\CategoryController;

// Адмінка
$groupData = [
    'prefix' => 'admin/blog',
];

Route::group($groupData, function () {
    // BlogCategory
    $methods = ['index', 'store', 'update'];

    Route::apiResource('categories', CategoryController::class)
        ->only($methods)
        ->names('blog.admin.categories');
});
