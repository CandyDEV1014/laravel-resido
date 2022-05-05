<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\RealEstate\Models\Currency;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Package extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_packages';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'currency_id',
        'credits',
        'number_of_days',
        'number_of_properties',
        'number_of_aminities',
        'number_of_nearestplace',
        'number_of_photo',
        'is_allow_featured',
        'number_of_featured',
        'is_allow_top',
        'number_of_top',
        'is_allow_urgent',
        'number_of_urgent',
        'is_promotion',
        'promotion_price',
        'promotion_time',
        'is_auto_renew',
        'is_agent',
        'order',
        'features',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class)->withDefault();
    }

    /**
     * @return BelongsToMany
     */
    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 're_accounts_packages', 'package_id', 'account_id');
    }
}
