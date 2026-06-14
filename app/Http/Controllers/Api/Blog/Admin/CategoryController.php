<?php

namespace App\Http\Controllers\Api\Blog\Admin;

//use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Repositories\BlogCategoryRepository;
use App\Http\Resources\Api\Blog\Admin\CategoryResource;

class CategoryController extends BaseController
{

    private BlogCategoryRepository $blogCategoryRepository;

    public function __construct(BlogCategoryRepository $blogCategoryRepository)
    {
        parent::__construct();
        $this->blogCategoryRepository = $blogCategoryRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);

        return CategoryResource::collection($paginator);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input(); // отримаємо масив даних, які надійшли з форми

        $item = (new BlogCategory())->create($data); // створюємо об'єкт і додаємо в БД

        if ($item) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       // dd(__METHOD__);//
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogCategoryUpdateRequest $request, string $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Запис id=[{$id}] не знайдено"])
                ->withInput();
        }

        $data = $request->all();

        $result = $item->update($data);

        if ($result) {
            return ['success' => 'Успішно збережено'];
        } else {
            return ['msg' => 'Помилка збереження'];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // dd(__METHOD__);//
    }
}
