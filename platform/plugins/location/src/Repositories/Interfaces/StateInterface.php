<?php

namespace Botble\Location\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface StateInterface extends RepositoryInterface
{
    /**
     * @param int $country_id
     * @return Collection
     */
    public function getStatesByCountry(int $country_id);
}
