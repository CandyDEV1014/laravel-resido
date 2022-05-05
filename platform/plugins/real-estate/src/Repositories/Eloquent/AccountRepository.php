<?php

namespace Botble\RealEstate\Repositories\Eloquent;

use Botble\RealEstate\Repositories\Interfaces\AccountInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use DB;
use Illuminate\Support\Str;

class AccountRepository extends RepositoriesAbstract implements AccountInterface
{
    /**
     * {@inheritDoc}
     */
    public function createUsername($name, $id = null)
    {
        $username = Str::slug($name);
        $index = 1;
        $baseSlug = $username;
        while ($this->model->where('username', $username)->where('id', '!=', $id)->count() > 0) {
            $username = $baseSlug . '-' . $index++;
        }

        if (empty($username)) {
            $username = $baseSlug . '-' . time();
        }

        $this->resetModel();

        return $username;
    }

    public function getAgents($params = [])
    {
        $this->model = $this->model
            ->Join('re_accounts_packages', function ($join) {
                $join->on('re_accounts.id', '=', 're_accounts_packages.account_id')
                    ->where('re_accounts_packages.status', '=', 1)
                    ->where(function($query)
                    {
                        $query->where('re_accounts_packages.expired_date', '>', date('Y-m-d H:i:s'))
                        ->orWhere('re_accounts_packages.expired_day', '=', -1);
                    });
            })
            ->Join('re_packages', function ($join) {
                $join->on('re_accounts_packages.package_id', '=', 're_packages.id');
            })
            ->where('re_packages.is_agent', '=', 1);

        return $this->advancedGet($params);
    }
}
