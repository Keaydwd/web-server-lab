<?php

namespace App\Repositories;

use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BlogCategoryRepository.
 */
class BlogCategoryRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class; // абстрагування моделі BlogCategory
    }

    /**
     * Отримати модель для редагування в адмінці
     * @param int $id
     * @return Model
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     * Отримати список категорій для виводу в випадаючий список
     * @return \Illuminate\Support\Collection
     */
    public function getForComboBox()
    {
        $columns = implode(', ', [
            'id',
            'CONCAT (id, ". ", title) AS id_title',  // додаємо поле id_title
        ]);

        $result = $this
            ->startConditions()
            ->selectRaw($columns)
            ->toBase()
            ->get();

        return $result;
    }

    /**
     * Отримати список категорій з пошуком і пагінацією.
     */
    public function getAllWithPaginate($perPage = 5, ?string $search = null)
    {
        $columns = [
            'id',
            'title',
            'slug',
            'description',
            'parent_id',
        ];

        $result = $this
            ->startConditions()
            ->select($columns)
            ->with(['parentCategory:id,title'])
            ->when($search, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('parentCategory', function ($query) use ($search) {
                            $query->where('title', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('id')
            ->paginate($perPage)
            ->appends([
                'search' => $search,
                'per_page' => $perPage,
            ]);

        return $result;
    }
    /**
     * Отримати всі категорії для списків у формах.
     */
    public function getAllForSelect()
    {
        return $this
            ->startConditions()
            ->select(['id', 'title', 'parent_id'])
            ->orderBy('id')
            ->get();
    }
}
