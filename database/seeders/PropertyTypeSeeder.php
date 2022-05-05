<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Language\Models\LanguageMeta;
use Botble\RealEstate\Models\Type;
use Botble\RealEstate\Models\TypeTranslation;
use Botble\Slug\Models\Slug;

class PropertyTypeSeeder extends BaseSeeder
{
    public function run()
    {
        Type::truncate();
        TypeTranslation::truncate();
        LanguageMeta::where('reference_type', Type::class)->delete();

        $types = [
            [
                'name'  => 'For Sale',
                'slug'  => 'sale',
                'code'  => 'sale',
                'order' => 0,
            ],
            [
                'name'  => 'For Rent',
                'slug'  => 'rent',
                'code'  => 'rent',
                'order' => 1,
            ],
        ];
        $translations = [
            [
                'name'  => 'Mua bán',
                'slug'  => 'mua-ban',
            ],
            [
                'name'  => 'Cho thuê',
                'slug'  => 'cho-thue',
            ],
        ];

        foreach ($types as $type) {
            Type::create($type);
        }

        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['re_property_types_id'] = $index + 1;

            TypeTranslation::insert($item);
        }
    }
}
