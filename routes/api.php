<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Blog\Admin\PostController;

use App\Http\Controllers\Api\Blog\Admin\CategoryController;


// Адмінка
$groupData = [
    'prefix' => 'admin/blog',
];

Route::group($groupData, function () {
    // BlogCategory
    $methods = ['index', 'store', 'update'];

    // BlogPost
    Route::apiResource('posts', PostController::class)
        ->except(['show'])                               // не робити маршрут для метода show
        ->names('blog.admin.posts');
});
