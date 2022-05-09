<?php

namespace Botble\RealEstate\Repositories\Caches;

use Botble\RealEstate\Repositories\Interfaces\DetailInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class DetailCacheDecorator extends CacheAbstractDecorator implements DetailInterface
{
  /**
     * {@inheritDoc}
     */
    public function getDetailsByCategory(int $category_id)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
