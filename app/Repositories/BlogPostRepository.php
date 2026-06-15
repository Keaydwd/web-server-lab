<?php

namespace App\Repositories;

use App\Models\BlogPost as Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BlogPostRepository.
 */
class BlogPostRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class; // абстрагування моделі BlogPost
    }

    /**
     * Отримати список статей з пошуком і пагінацією.
     */
    public function getAllWithPaginate($perPage = 25, ?string $search = null)
    {
        $columns = [
            'id',
            'title',
            'slug',
            'excerpt',
            'is_published',
            'published_at',
            'user_id',
            'category_id',
        ];

        $result = $this
            ->startConditions()
            ->select($columns)
            ->with([
                'category:id,title',
                'user:id,name',
            ])
            ->when($search, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($query) use ($search) {
                            $query->where('title', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('id', 'DESC')
            ->paginate($perPage)
            ->appends([
                'search' => $search,
                'per_page' => $perPage,
            ]);

        return $result;
    }

    public function getOneById(int $id)
    {
        return $this
            ->startConditions()
            ->with([
                'category:id,title',
                'user:id,name',
            ])
            ->find($id);
    }

    /**
     * Отримати модель для редагування в адмінці
     * @param int $id
     * @return Model
     */
    public function getEdit($id)
    {
        return $this
            ->startConditions()
            ->with([
                'category:id,title',
                'user:id,name',
            ])
            ->find($id);
    }
}
