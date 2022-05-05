<?php

namespace Botble\RealEstate\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\RealEstate\Repositories\Interfaces\CategoryInterface;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Language;

class CategoryRepository extends RepositoriesAbstract implements CategoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getCategories(array $select, array $orderBy, array $conditions = [])
    {
        $data = $this->model->with('slugable')->select($select);
        if ($conditions) {
            $data = $data->where($conditions);
        }
        foreach ($orderBy as $by => $direction) {
            $data = $data->orderBy($by, $direction);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getSubcategories(int $parent_id)
    {
        $data = $this->model
            ->select("*")
            ->where('re_categories.status', BaseStatusEnum::PUBLISHED)
            ->where('re_categories.parent_id', $parent_id)
            ->orderBy('re_categories.order', 'desc');

        if (is_plugin_active('language-advanced') && Language::getCurrentLocale() != Language::getDefaultLocale()) {
            $data = $data
                ->join('re_categories_translations', 're_categories.id', '=', 're_categories_translations.re_categories_id');
        } 

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
