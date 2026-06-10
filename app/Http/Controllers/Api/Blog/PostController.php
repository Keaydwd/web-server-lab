<?php

namespace App\Http\Controllers\Api\Blog;

use App\Repositories\BlogPostRepository;

class PostController extends BaseController
{
    public function __construct(
        private BlogPostRepository $blogPostRepository
    ) {
    }

    /**
     * Отримати список статей.
     */
    public function index()
    {
        return $this->blogPostRepository->getAllWithPaginate();
    }
}
