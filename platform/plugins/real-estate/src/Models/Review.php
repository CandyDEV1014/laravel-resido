<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends BaseModel
{
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 're_reviews';

    /**
     * @var array
     */
    protected $fillable = [
        'reviewable_id',
        'reviewable_type',
        'account_id',
        'star',
        'comment',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * Get the parent reviewable model (property).
     */
    public function reviewable()
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class)->withDefault();
    }

    /**
     * @return HasMany
     */
    public function meta()
    {
        return $this->hasMany(ReviewMeta::class, 'review_id');
    }
}
