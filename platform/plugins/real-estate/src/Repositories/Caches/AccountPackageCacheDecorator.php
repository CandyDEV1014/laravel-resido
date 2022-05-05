<?php

namespace Botble\RealEstate\Repositories\Caches;

use Botble\RealEstate\Repositories\Interfaces\AccountPackageInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class AccountPackageCacheDecorator extends CacheAbstractDecorator implements AccountPackageInterface
{
    /**
     * {@inheritDoc}
     */
    public function getActivePackage($accountId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function isExpired($accountId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
