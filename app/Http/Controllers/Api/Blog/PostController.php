<?php

namespace App\Http\Controllers\Api\Blog;

use App\Repositories\BlogPostRepository;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    public function __construct(
        private BlogPostRepository $blogPostRepository
    ) {
    }

    /**
     * Отримати список статей.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 25);
        $perPage = max(1, min($perPage, 100));

        $search = trim((string) $request->input('search', ''));
        $search = $search !== '' ? $search : null;

        return $this->blogPostRepository->getAllWithPaginate($perPage, $search);
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
