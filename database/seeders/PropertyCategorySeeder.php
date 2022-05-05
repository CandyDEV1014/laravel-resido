<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Language\Models\LanguageMeta;
use Botble\RealEstate\Models\Category;
use Botble\RealEstate\Models\CategoryTranslation;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Str;
use SlugHelper;

class PropertyCategorySeeder extends BaseSeeder
{
    public function run()
    {
        Category::truncate();
        CategoryTranslation::truncate();
        Slug::where('reference_type', Category::class)->delete();
        LanguageMeta::where('reference_type', Category::class)->delete();

        $categories = [
            [
                'name'       => 'Apartment',
                'is_default' => true,
                'order'      => 0,
            ],
            [
                'name'       => 'Villa',
                'is_default' => false,
                'order'      => 1,
            ],
            [
                'name'       => 'Condo',
                'is_default' => false,
                'order'      => 2,
            ],
            [
                'name'       => 'House',
                'is_default' => false,
                'order'      => 3,
            ],
            [
                'name'       => 'Land',
                'is_default' => false,
                'order'      => 4,
            ],
            [
                'name'       => 'Commercial property',
                'is_default' => false,
                'order'      => 5,
            ],
        ];
        $translations = [
            [
                'name'       => 'Căn hộ dịch vụ',
            ],
            [
                'name'       => 'Biệt thự',
            ],
            [
                'name'       => 'Căn hộ',
            ],
            [
                'name'       => 'Nhà',
            ],
            [
                'name'       => 'Đất',
            ],
            [
                'name'       => 'Bất động sản thương mại',
            ],
        ];
        foreach ($categories as $item) {
            $category = Category::create($item);

            Slug::create([
                'reference_type' => Category::class,
                'reference_id'   => $category->id,
                'key'            => Str::slug($category->name),
                'prefix'         => SlugHelper::getPrefix(Category::class),
            ]);
        }
        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['re_categories_id'] = $index + 1;
            CategoryTranslation::insert($item);
        }
    }
}
