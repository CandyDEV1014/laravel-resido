<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Language\Models\LanguageMeta;
use Botble\Location\Models\City;
use Botble\Location\Models\CityTranslation;
use Botble\Location\Models\Country;
use Botble\Location\Models\CountryTranslation;
use Botble\Location\Models\State;
use Botble\Location\Models\StateTranslation;
use MetaBox;

class LocationSeeder extends BaseSeeder
{
    public function run()
    {
        $this->uploadFiles('cities');
        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            LanguageMeta::whereIn('reference_type', [City::class, State::class, Country::class])->delete();
        }

        City::truncate();
        State::truncate();
        Country::truncate();
        CityTranslation::truncate();
        StateTranslation::truncate();
        CountryTranslation::truncate();

        $this->createDataForUs();
        $this->createDataForCanada();
    }

    protected function createDataForUs()
    {
        Country::create([
            'id'          => 1,
            'name'        => 'United States of America',
            'nationality' => 'Americans',
            'is_featured'  => 1,
            'status'      => 'published',
            'order'       => 0,
        ]);

        $states = file_get_contents(database_path('seeders/files/us/states.json'));
        $states = json_decode($states, true);
        foreach ($states as $state) {
            State::create($state);
        }

        $cities = file_get_contents(database_path('seeders/files/us/cities.json'));
        $cities = json_decode($cities, true);
        foreach ($cities as $index => $item) {
            if (City::where('name', $item['fields']['city'])->count() > 0) {
                continue;
            }

            $state = State::where('abbreviation', $item['fields']['state_code'])->first();
            if (!$state) {
                continue;
            }

            $city = [
                'name'       => $item['fields']['city'],
                'state_id'   => $state->id,
                'country_id' => 1,
            ];

            if($index < 6) {
                $city['is_featured'] = 1;
            }

            $cityObject = City::create($city);

            $slug = $city['name'];
            if (function_exists('create_city_slug')) {
                $slug = create_city_slug($slug, $cityObject);
            }
            $cityObject->slug = $slug;
            $cityObject->save();

            MetaBox::saveMetaBoxData($cityObject, 'image', 'cities/c-' . ($index + 1) . '.png');
        }
    }

    protected function createDataForCanada()
    {
        Country::create([
            'id'          => 2,
            'name'        => 'Canada',
            'nationality' => 'Canada',
            'is_featured'  => 0,
            'status'      => 'published',
            'order'       => 1,
        ]);

        $states = file_get_contents(database_path('seeders/files/ca/states.json'));
        $states = json_decode($states, true);
        foreach ($states as $state) {
            State::create($state);
        }

        $cities = file_get_contents(database_path('seeders/files/ca/cities.json'));
        $cities = json_decode($cities, true);
        foreach ($cities as $item) {

            $state = State::where('name', $item['name'])->first();
            if (!$state) {
                continue;
            }

            foreach ($item['cities'] as $cityName) {
                $city = [
                    'name'       => $cityName,
                    'state_id'   => $state->id,
                    'country_id' => 2,
                ];

                $cityObject = City::create($city);
                $slug = $city['name'];
                if (function_exists('create_city_slug')) {
                    $slug = create_city_slug($slug, $cityObject);
                }
                $cityObject->slug = $slug;
                $cityObject->save();
            }
        }
    }
}
