<?php

namespace Botble\Newsletter\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Newsletter\Enums\NewsletterStatusEnum;

class Newsletter extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'newsletters';

    /**
     * @var array
     */
    protected $fillable = [
        'email',
        'name',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => NewsletterStatusEnum::class,
    ];
}
