<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\BlogPostUpdateRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Http\Requests\BlogPostCreateRequest;

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
        $data = $request->input(); // отримаємо масив даних, які надійшли з форми
        $item = (new BlogPost())->create($data); // створюємо об'єкт і додаємо в БД

        if ($item) {
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
        $result = BlogPost::destroy($id); // софт деліт, запис лишається в базі, але стає "невидимим"
        // $result = BlogPost::find($id)->forceDelete(); // повне видалення з БД

        if ($result) {
            return ['success' => true, 'message' => "Запис з id [$id] успішно видалено"];
        } else {
            return ['success' => false, 'message' => 'Помилка видалення або запис не знайдено'];
        }
    }
}
