<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Models\BaseModel;

class TypeTranslation extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_property_types_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        're_types_id',
        'name',
        'slug'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
