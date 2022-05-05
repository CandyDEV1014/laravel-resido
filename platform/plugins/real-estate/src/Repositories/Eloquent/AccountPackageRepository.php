<?php

namespace Botble\RealEstate\Repositories\Eloquent;

use Botble\RealEstate\Repositories\Interfaces\AccountPackageInterface;
use Botble\RealEstate\Repositories\Interfaces\PackageInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Support\Str;

class AccountPackageRepository extends RepositoriesAbstract implements AccountPackageInterface
{
    /**
     * {@inheritDoc}
     */
    public function getActivePackage($accountId)
    {
        // $data = $this->model->where('status', 1)->where('account_id', $accountId)->first();
        // $result = array();
        // if ($data) {
        //     $package = app(PackageInterface::class)->getFirstBy(['id' => $data->package_id]);
        //     $collection = collect($data);
        //     $merged = $collection->merge($package);
        //     $result = $merged->all();
        // }
        
        // return $result;

        $result =  $this->model
            ->select('*')
            ->Join('re_packages', function ($join) {
                $join->on('re_accounts_packages.package_id', '=', 're_packages.id');
            })
            ->where('re_accounts_packages.account_id', '=', $accountId)
            ->where('re_accounts_packages.status', '=', 1)
            ->where(function($query)
            {
                $query->where('re_accounts_packages.expired_date', '>', date('Y-m-d H:i:s'))
                ->orWhere('re_accounts_packages.expired_day', '=', -1);
            })
            ->get()
            ->first();
        
        if ($result) {
            return $result->toArray();
        }
        return array();
    }

    public function isExpired($accountId)
    {
        $data = $this->model->where('status', 1)->where('account_id', $accountId)->first();
        if ($data) {
            if ( $data->expired_day != -1 && strtotime($data->expired_date) < strtotime('now')) {
                return true;
            }
        }
        return false;
    }
}
