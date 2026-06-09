<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\BlogPostUpdateRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Http\Requests\BlogPostCreateRequest;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;

class PostController extends BaseController
{
    private BlogCategoryRepository $blogCategoryRepository;

    public function __construct(
        private BlogPostRepository $blogPostRepository,
        BlogCategoryRepository $blogCategoryRepository
    ) {
        parent::__construct();
        $this->blogCategoryRepository = $blogCategoryRepository;
    }

    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate();
        return $paginator;
    }

    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->input();
        $item = (new BlogPost())->create($data);

        if ($item) {
            // Викликаємо Job створення
            BlogPostAfterCreateJob::dispatch($item);

            return ['success' => true, 'message' => 'Успішно збережено', 'id' => $item->id];
        } else {
            return ['success' => false, 'message' => 'Помилка збереження'];
        }
    }

    public function update(BlogPostUpdateRequest $request, string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);

        if (empty($item)) {
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $data = $request->all();

        // Обсервер сам перехопить модель всередині цього методу оновлення!
        $result = $item->update($data);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }

    public function destroy(string $id)
    {
        $result = BlogPost::destroy($id);

        if ($result) {
            // Викликаємо Job видалення із затримкою у 20 секунд
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);

            return ['success' => true, 'message' => "Запис з id [$id] успішно видалено"];
        } else {
            return ['success' => false, 'message' => 'Помилка видалення або запис не знайдено'];
        }
    }
}
