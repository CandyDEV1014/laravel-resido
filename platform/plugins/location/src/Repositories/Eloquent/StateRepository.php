<?php

namespace Botble\Location\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Location\Repositories\Interfaces\StateInterface;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Language;

class StateRepository extends RepositoriesAbstract implements StateInterface
{
    /**
     * {@inheritDoc}
     */
    public function getStatesByCountry(int $country_id)
    {
        $data = $this->model
            ->select("*")
            ->where('states.status', BaseStatusEnum::PUBLISHED)
            ->where('states.country_id', $country_id)
            ->orderBy('states.order', 'desc');

        if (is_plugin_active('language-advanced') && Language::getCurrentLocale() != Language::getDefaultLocale()) {
            $data = $data
                ->join('states_translations', 'states.id', '=', 'states_translations.states_id');
        } 

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
