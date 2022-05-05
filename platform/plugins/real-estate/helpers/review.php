<?php
use Botble\RealEstate\Repositories\Interfaces\ReviewInterface;

if (!function_exists('check_if_reviewed')) {
    /**
     * @param int $objectId
     * @param int $accountId
     * @return bool
     */
    function check_if_reviewed($reviewableId, $reviewableType = 'Botble\RealEstate\Models\Property', $accountId = null)
    {
        if ($accountId == null && auth('account')->check()) {
            $accountId = auth('account')->id();
        }

        $existed = app(ReviewInterface::class)->count([
            'account_id' => $accountId,
            'reviewable_id'  => $reviewableId,
            'reviewable_type'  => $reviewableType,
        ]);

        return $existed > 0;
    }
}

if (!function_exists('check_if_post_reviewed')) {
    /**
     * @param int $objectId
     * @param int $accountId
     * @return bool
     */
    function check_if_post_reviewed($reviewableId, $reviewableType = 'Botble\Blog\Models\Post', $accountId = null)
    {
        if ($accountId == null && auth('account')->check()) {
            $accountId = auth('account')->id();
        }

        $existed = app(ReviewInterface::class)->count([
            'account_id' => $accountId,
            'reviewable_id'  => $reviewableId,
            'reviewable_type'  => $reviewableType,
        ]);
        return $existed > 0;
    }
}

if (!function_exists('is_review_enabled')) {
    /**
     * @return bool
     */
    function is_review_enabled(): bool
    {
        return get_real_estate_setting('review_enabled', '1') == '1';
    }
}

if (!function_exists('get_review_fields')) {
    /**
     * @param null $default
     * @return array
     */
    function get_review_fields() : array
    {
        $array_fields = json_decode(setting('real_estate_review_fields'), true);
        $review_fields = [];
        foreach($array_fields as $item) {
            $review_fields[] = [$item[0]['key'] => $item[0]['value']];
        }
        return $review_fields;
    }
}

if (!function_exists('get_real_estate_setting')) {
    /**
     * @param string $key
     * @param null $default
     * @return string
     */
    function get_real_estate_setting($key, $default = '')
    {
        return setting(config('plugins.real-estate.real-estate.prefix') . $key, $default);
    }
}