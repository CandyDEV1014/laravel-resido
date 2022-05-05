<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Models\BaseModel;

class AccountPackage extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_accounts_packages';

    /**
     * @var array
     */
    protected $fillable = [
        'account_id',
        'package_id',
        'expired_day',
        'expired_date',
        'decimals',
        'status',
    ];
}
