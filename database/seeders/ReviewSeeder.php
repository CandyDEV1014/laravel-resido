<?php

namespace Database\Seeders;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\BaseSeeder;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Models\Review;
use Botble\RealEstate\Models\ReviewMeta;
use Faker\Factory;

class ReviewSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Review::truncate();
        ReviewMeta::truncate();
        $properties = Property::all();
        foreach ($properties as $property) {
            for($i = 0; $i < $faker->numberBetween(3, 8); $i++) {
                $meta = [
                    'service'     => $faker->numberBetween(1, 5),
                    'value'       => $faker->numberBetween(1, 5),
                    'location'    => $faker->numberBetween(1, 5),
                    'cleanliness' => $faker->numberBetween(1, 5),
                ];
                $sum = 0;
    
                foreach ($meta as $value) {
                    $sum += $value;
                }
    
                $star = $sum / count($meta);
    
                $review = Review::create([
                    'reviewable_id'   => $property->id,
                    'reviewable_type' => Property::class,
                    'account_id'      => $faker->numberBetween(1, 11),
                    'star'            => $star,
                    'comment'         => $faker->text(150),
                    'status'          => BaseStatusEnum::PUBLISHED,
                ]);
    
                foreach ($meta as $key => $value) {
                    ReviewMeta::setMeta($key, $value, $review->id);
                }
            }
        }
    }
}
