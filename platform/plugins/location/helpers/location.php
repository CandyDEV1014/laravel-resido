<?php
use Botble\Location\Repositories\Interfaces\CityInterface;
use Illuminate\Support\Str;

if (!function_exists('create_city_slug')) {
    /**
     * @param string $slug
     * @param City $city
     * @return int|string
     */
    function create_city_slug($slug, $city) {
        $slug = Str::slug($slug, '-', !SlugHelper::turnOffAutomaticUrlTranslationIntoLatin() ? 'en' : false);

        $index = 1;
        $baseSlug = $slug;

        while (app(CityInterface::class)->getModel()->where('slug', $slug)->where('id', '!=', $city->id)->count() > 0) {
            if ($slug == $baseSlug) {
                $slug = $baseSlug . '-' . Str::slug($city->state->name, '-', !SlugHelper::turnOffAutomaticUrlTranslationIntoLatin() ? 'en' : false);
            } else {
                $slug = $baseSlug . '-' . $index++;
            }
        }

        if (empty($slug)) {
            $slug = time();
        }

        return $slug;
    }
}