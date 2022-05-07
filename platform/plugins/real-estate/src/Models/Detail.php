<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Detail extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_details';

    /**
     * @var bool disable timestamp
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'title',
        'alt',
        'icon',
        'type',
        'features',
        'order',
        'is_featured',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * @return BelongsToMany
     */
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 're_property_details', 'detail_id', 'property_id');
    }
}
