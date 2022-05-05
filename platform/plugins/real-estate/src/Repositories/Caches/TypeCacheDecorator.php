<?php

namespace Botble\RealEstate\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\RealEstate\Repositories\Interfaces\TypeInterface;

class TypeCacheDecorator extends CacheAbstractDecorator implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function createSlug($name, $id)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
