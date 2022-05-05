<?php

namespace Botble\Testimonial\Models;

use Botble\Base\Models\BaseModel;

class TestimonialTranslation extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'testimonials_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'testimonials_id',
        'name',
        'content',
        'company',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
