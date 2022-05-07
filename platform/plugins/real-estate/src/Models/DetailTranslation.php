<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Models\BaseModel;

class DetailTranslation extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_packages_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        're_details_id',
        'name',
        'features',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
