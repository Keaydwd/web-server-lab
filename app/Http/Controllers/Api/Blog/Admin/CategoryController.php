<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Models\BlogCategory;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Repositories\BlogCategoryRepository;
use App\Http\Resources\Api\Blog\Admin\CategoryResource;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    private BlogCategoryRepository $blogCategoryRepository;

    public function __construct(BlogCategoryRepository $blogCategoryRepository)
    {
        parent::__construct();
        $this->blogCategoryRepository = $blogCategoryRepository;
    }

    /**
     * Список категорій з пагінацією.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 5);
        $perPage = max(1, min($perPage, 100));

        $search = trim((string) $request->input('search', ''));
        $search = $search !== '' ? $search : null;

        $paginator = $this->blogCategoryRepository->getAllWithPaginate($perPage, $search);

        return CategoryResource::collection($paginator);
    }

    /**
     * Список усіх категорій для випадаючих списків.
     */
    public function list()
    {
        $items = $this->blogCategoryRepository->getAllForSelect();

        return CategoryResource::collection($items);
    }

    /**
     * Створити категорію.
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();

        $item = (new BlogCategory())->create($data);

        if ($item) {
            return [
                'success' => true,
                'message' => 'Успішно збережено',
                'id' => $item->id,
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка збереження',
        ];
    }

    /**
     * Отримати одну категорію.
     */
    public function show(string $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return response()->json([
                'message' => "Категорію з id={$id} не знайдено",
            ], 404);
        }

        return new CategoryResource($item);
    }

    /**
     * Оновити категорію.
     */
    public function update(BlogCategoryUpdateRequest $request, string $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return response()->json([
                'success' => false,
                'message' => "Запис id=[{$id}] не знайдено",
            ], 404);
        }

        $data = $request->all();

        $result = $item->update($data);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Успішно збережено',
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка збереження',
        ];
    }

    /**
     * Видалити категорію.
     */
    public function destroy(string $id)
    {
        if ((int) $id === BlogCategory::ROOT) {
            return response()->json([
                'success' => false,
                'message' => 'Кореневу категорію видаляти не можна',
            ], 422);
        }

        $result = BlogCategory::destroy($id);

        if ($result) {
            return [
                'success' => true,
                'message' => "Категорію з id [$id] успішно видалено",
            ];
        }

        return response()->json([
            'success' => false,
            'message' => 'Помилка видалення або запис не знайдено',
        ], 404);
    }
}
