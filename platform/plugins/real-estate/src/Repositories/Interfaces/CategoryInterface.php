<?php

namespace Botble\RealEstate\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface CategoryInterface extends RepositoryInterface
{
    /**
     * @param array $select
     * @param array $orderBy
     * @return Collection
     */
    public function getCategories(array $select, array $orderBy);

    /**
     * @param int $parent_id
     * @return Collection
     */
    public function getSubcategories(int $parent_id);
}
