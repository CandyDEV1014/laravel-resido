<?php

namespace Botble\RealEstate\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface AccountPackageInterface extends RepositoryInterface
{
    /**
     * @param string $accountId
     * @return Collection
     */
    public function getActivePackage($accountId);
    public function isExpired($accountId);
}
