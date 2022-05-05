<?php

use Botble\Location\Models\City;

// Custom routes
Route::group(['namespace' => 'Theme\Resido\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::get(SlugHelper::getPrefix(City::class, 'city') . '/{slug}',
            'ResidoController@getPropertiesByCity')
            ->name('public.properties-by-city');

        Route::get('agents', 'ResidoController@getAgents')->name('public.agents');
        Route::get('agents/{username}', 'ResidoController@getAgent')->name('public.agent');

        Route::get('wishlist', 'ResidoController@getWishlist')->name('public.wishlist');

        Route::get('ajax/stateses-by-country', 'ResidoController@ajaxGetStatesByCountry')->name('public.ajax.stateses-by-country');
        Route::get('ajax/cities-by-state', 'ResidoController@ajaxGetCitiesByState')->name('public.ajax.cities-by-state');
        Route::get('ajax/cities', 'ResidoController@ajaxGetCities')->name('public.ajax.cities');

        Route::get('ajax/properties', 'ResidoController@ajaxGetProperties')->name('public.ajax.properties');
        Route::get('ajax/posts', 'ResidoController@ajaxGetPosts')->name('public.ajax.posts');
        Route::post('ajax/properties/map', 'ResidoController@ajaxGetPropertiesForMap')
            ->name('public.ajax.properties.map');

        Route::get('ajax/agents/featured', 'ResidoController@ajaxGetFeaturedAgents')
            ->name('public.ajax.featured-agents');

        Route::get('ajax/testimonials', 'ResidoController@ajaxGetTestimonials')
            ->name('public.ajax.testimonials');
        Route::get('ajax/real-estate-reviews/{id}', 'ResidoController@ajaxGetRealEstateReviews')
            ->name('public.ajax.real-estate-reviews');
        Route::get('ajax/real-estate-rating/{id}', 'ResidoController@ajaxGetRealEstateRating')
            ->name('public.ajax.real-estate-rating');
        Route::get('ajax/post-reviews/{id}', 'ResidoController@ajaxGetPostReviews')
            ->name('public.ajax.post-reviews');

        Route::get('ajax/sub-categories', 'ResidoController@ajaxGetSubCategories')->name('public.ajax.sub-categories');
    });
});

Theme::routes();

Route::group(['namespace' => 'Theme\Resido\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

        Route::get('/', 'ResidoController@getIndex')->name('public.index');

        Route::get('sitemap.xml', [
            'as'   => 'public.sitemap',
            'uses' => 'ResidoController@getSiteMap',
        ]);

        Route::get('{slug?}' . config('core.base.general.public_single_ending_url'), [
            'as'   => 'public.single',
            'uses' => 'ResidoController@getView',
        ]);

    });

});
