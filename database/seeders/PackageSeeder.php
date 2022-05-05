<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\RealEstate\Models\Package;
use Botble\RealEstate\Models\PackageTranslation;
use Botble\Language\Models\LanguageMeta;

class PackageSeeder extends BaseSeeder
{
    public function run()
    {
        Package::truncate();
        PackageTranslation::truncate();
        LanguageMeta::where('reference_type', Package::class)->delete();
        $packages = [
            [
                'name'               => 'Free First Post',
                'price'              => 0,
                'currency_id'        => 1,
                'percent_save'       => 0,
                'order'              => 0,
                'number_of_listings' => 1,
                'account_limit'      => 1,
                'is_default'         => false,
            ],
            [
                'name'               => 'Single Post',
                'price'              => 250,
                'currency_id'        => 1,
                'percent_save'       => 0,
                'order'              => 0,
                'number_of_listings' => 1,
                'is_default'         => true,
            ],
            [
                'name'               => '5 Posts',
                'price'              => 1000,
                'currency_id'        => 1,
                'percent_save'       => 20,
                'order'              => 0,
                'number_of_listings' => 5,
                'is_default'         => false,
            ],
        ];

        $translations = [
            [
                'name'               => 'Miễn phí',
            ],
            [
                'name'               => 'Một post',
            ],
            [
                'name'               => '5 posts',
            ]
        ];
        foreach ($packages as $index => $item) {
            $item['features'] = '[[{"key":"text","value":"Buy credits to post your listing(s)"}],[{"key":"text","value":"60-Day Job Postings"}],[{"key":"text","value":"No Expiration on Credits."}],[{"key":"text","value":"Specialist Assistance"}],[{"key":"text","value":"Get More Eyes"}]]';
            $package = Package::create($item);
        }

        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['re_packages_id'] = $index + 1;
            $item['features'] = '[[{"key":"text","value":"Mua tín dụng để đăng tin của bạn"}],[{"key":"text","value":"Trong 60 ngày"}],[{"key":"text","value":"Không hết hạn đối với các khoản tín dụng."}],[{"key":"text","value":"Hỗ trợ của Chuyên gia"}],[{"key":"text","value":"Có thêm đôi mắt"}]]';
            PackageTranslation::insert($item);
        }

    }
}
