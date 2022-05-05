<?php

namespace Botble\Block\Models;

use Botble\Base\Models\BaseModel;

class BlockTranslation extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'blocks_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'blocks_id',
        'name',
        'description',
        'content',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
