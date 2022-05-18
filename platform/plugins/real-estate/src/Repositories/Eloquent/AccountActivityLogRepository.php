<?php

namespace Botble\RealEstate\Repositories\Eloquent;

use Botble\RealEstate\Repositories\Interfaces\AccountActivityLogInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Builder;

class AccountActivityLogRepository extends RepositoriesAbstract implements AccountActivityLogInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAllLogs($accountId, $paginate = 10)
    {
        return $this->model
            ->where('account_id', $accountId)
            ->latest('created_at')
            ->paginate($paginate);
    }

    /**
     * {@inheritdoc}
     */
    public function getMyReviewAllLogs($accountId, $paginate = 10)
    {
        return $this->model
            ->where('account_id', $accountId)
            ->where(function (Builder $query) {
                return $query
                    ->where('action', '=', 'add_my_review')
                    ->orWhere('action', '=', 'update_my_review');
            })
            ->latest('created_at')
            ->paginate($paginate);
    }

    /**
     * {@inheritdoc}
     */
    public function getClientReviewAllLogs($accountId, $paginate = 10)
    {
        return $this->model
            ->where('account_id', $accountId)
            ->where(function (Builder $query) {
                return $query
                    ->where('action', '=', 'add_client_review')
                    ->orWhere('action', '=', 'update_client_review');
            })
            ->latest('created_at')
            ->paginate($paginate);
    }
}
