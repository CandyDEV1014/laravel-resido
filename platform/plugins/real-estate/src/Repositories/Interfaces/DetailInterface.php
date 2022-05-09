<?php

namespace Botble\RealEstate\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface DetailInterface extends RepositoryInterface
{
  /**
     * @param int $parent_id
     * @return Collection
     */
    public function getDetailsByCategory(int $category_id);
}
