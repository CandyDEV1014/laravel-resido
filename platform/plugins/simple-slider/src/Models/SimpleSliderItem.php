<?php

namespace Botble\SimpleSlider\Models;

use Botble\Base\Models\BaseModel;

class SimpleSliderItem extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'simple_slider_items';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'link',
        'image',
        'order',
        'simple_slider_id',
    ];
}
