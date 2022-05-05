<?php

namespace Botble\Location\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface CityInterface extends RepositoryInterface
{
    /**
     * @param string $keyword
     * @param int $perPage
     * @param array $with
     * @param array|string[] $select
     */
    public function filters($keyword, $perPage = 10, array $with = [], array $select = ['cities.*']);

    /**
     * @param array $args
     * @return Collection
     */
    public function getFeaturedCities($args = []);

    /**
     * @param int $state_id
     * @return Collection
     */
    public function getCitiesByState(int $state_id);
}
