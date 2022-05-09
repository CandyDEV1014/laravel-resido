<?php

namespace Botble\RealEstate\Repositories\Eloquent;

use Botble\RealEstate\Repositories\Interfaces\DetailInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class DetailRepository extends RepositoriesAbstract implements DetailInterface
{
  /**
     * {@inheritDoc}
     */
    public function getDetailsByCategory(int $category_id)
    {
        $data = $this->model->categories()->get();

        $data = $this->model
            ->select('re_details.*')
            ->Join('re_detail_categories', function ($join) use ($category_id) {
                $join->on('re_details.id', '=', 're_detail_categories.detail_id')
                    ->where('re_detail_categories.category_id', '=', $category_id);
            })
            ->groupBy('re_details.id');

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
