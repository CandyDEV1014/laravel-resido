<?php

namespace Botble\RealEstate\Models;

use Illuminate\Support\Facades\Auth;
use Botble\Base\Models\BaseModel;

class ReviewMeta extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_reviews_meta';

    public $timestamps = false;
    
    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
        'review_id',
    ];

    /**
     * The date fields for the model.clear
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @param string $key
     * @param null $value
     * @param int $reviewId
     * @return bool
     */
    public static function setMeta($key, $value = null, $reviewId = 0)
    {
        $meta = self::firstOrCreate([
            'review_id' => $reviewId,
            'key'     => $key,
        ]);

        return $meta->update(['value' => $value]);
    }

    /**
     * @param string $key
     * @param null $default
     * @param int $reviewId
     * @return string
     */
    public static function getMeta($key, $default = null, $reviewId = 0)
    {

        $meta = self::where([
            'review_id' => $reviewId,
            'key'     => $key,
        ])->select('value')->first();

        if (!empty($meta)) {
            return $meta->value;
        }

        return $default;
    }
}
