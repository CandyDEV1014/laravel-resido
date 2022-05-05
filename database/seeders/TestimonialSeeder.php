<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Language\Models\LanguageMeta;
use Botble\Menu\Models\Menu as MenuModel;
use Botble\Testimonial\Models\Testimonial;
use Botble\Testimonial\Models\TestimonialTranslation;
use Faker\Factory;

class TestimonialSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Testimonial::truncate();
        TestimonialTranslation::truncate();
        LanguageMeta::where('reference_type', Testimonial::class)->delete();
        
        $this->uploadFiles('testimonials');

        $testimonials = [
            [
                'name'    => 'Adam Williams',
                'company' => 'CEO Of Microwoft',
            ],
            [
                'name'    => 'Retha Deowalim',
                'company' => 'CEO Of Apple',
            ],
            [
                'name'    => 'Sam J. Wasim',
                'company' => 'Pio Founder',
            ],
            [
                'name'    => 'Usan Gulwarm',
                'company' => 'CEO Of Facewarm',
            ],
            [
                'name'    => 'Shilpa Shethy',
                'company' => 'CEO Of Zapple',
            ],
        ];
        $translations= [
            [
                'name'    => 'Adam Williams',
                'company' => 'Giám đốc Microwoft',
            ],
            [
                'name'    => 'Retha Deowalim',
                'company' => 'Giám đốc Apple',
            ],
            [
                'name'    => 'Sam J. Wasim',
                'company' => 'Nhà sáng lập Pio',
            ],
            [
                'name'    => 'Usan Gulwarm',
                'company' => 'Giám đốc Facewarm',
            ],
            [
                'name'    => 'Shilpa Shethy',
                'company' => 'Giám đốc Zapple',
            ],
        ];

        
        $faker = Factory::create();

        foreach ($testimonials as $index => $item) {
            $item['image'] = 'testimonials/' . ($index + 1) . '.jpg';
            $item['content'] = $faker->realText(50);
            $testimonial = Testimonial::create($item);
        }
        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['content'] = $faker->realText(50);
            $item['testimonials_id'] = $index + 1;
            TestimonialTranslation::insert($item);
        }
    }
}
