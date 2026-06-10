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

    /**
     * Отримати один пост.
     */
    public function show(int $id)
    {
        $post = $this->blogPostRepository->getOneById($id);

        if (empty($post)) {
            return response()->json([
                'message' => "Пост з id={$id} не знайдено",
            ], 404);
        }

        return $post;
    }
}
