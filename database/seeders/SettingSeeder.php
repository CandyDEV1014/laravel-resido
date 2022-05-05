<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Location\Models\City;
use Botble\Setting\Models\Setting as SettingModel;
use Botble\Slug\Models\Slug;
use SlugHelper;

class SettingSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SettingModel::whereIn('key', ['media_random_hash'])->delete();

        SettingModel::insertOrIgnore([
            [
                'key'   => 'media_random_hash',
                'value' => md5(time()),
            ],
        ]);

        SettingModel::insertOrIgnore([
            [
                'key'   => 'payment_bank_transfer_status',
                'value' => '1'
            ],
            [
                'key'   => 'payment_stripe_name',
                'value' => 'Pay online via Stripe'
            ],
            [
                'key'   => 'payment_stripe_description',
                'value' => 'Payment with Stripe'
            ],
            [
                'key'   => 'payment_stripe_client_id',
                'value' => 'pk_test_51JGbXaLmHOfJnFasFfg5ksgorTTlnnqt8RzdrT1qIjqg5sGcVK3fNWBZu1OZ84ndCI4fo0Bdm7TL1yLfiC6e7nF700hBjwxbKa'
            ],
            [
                'key'   => 'payment_stripe_secret',
                'value' => 'sk_test_51JGbXaLmHOfJnFasntP9rqE8wZ0qghWeMKcdlQphvMNo7C2sVaTFgRcjlnof8XVBRZspgVk7ctO62QlY10E8rHNT002pnOk3VI'
            ],
            [
                'key'   => 'payment_stripe_status',
                'value' => '1'
            ],
            [
                'key'   => 'payment_paypal_name',
                'value' => 'Pay online via PayPal'
            ],
            [
                'key'   => 'payment_paypal_description',
                'value' => 'Payment with PayPal'
            ],
            [
                'key'   => 'payment_paypal_client_id',
                'value' => 'AZlbcwqaPAMIZ27JGOMRYrkWlMdvylKDgoNwS6rzww4_Q2naixJ9KwoOgDdhkwXBro7yTxKOV1hADDMO'
            ],
            [
                'key'   => 'payment_paypal_secret',
                'value' => 'EG7289O15aSq5bT_XLY0VhA0slkme6rlKTBp0Z1KyGY-cJoq7kPIxAvDbpVd8npd_-jBHcnYuMjF9CV3'
            ],
            [
                'key'   => 'payment_paypal_status',
                'value' => '1'
            ],
            [
                'key'   => 'real_estate_square_unit',
                'value' => 'm²'
            ],
            [
                'key'   => 'real_estate_convert_money_to_text_enabled',
                'value' => '1'
            ],
            [
                'key'   => 'real_estate_thousands_separator',
                'value' => ','
            ],
            [
                'key'   => 'real_estate_decimal_separator',
                'value' => '.'
            ],
            [
                'key'   => 'real_estate_enabled_register',
                'value' => '1'
            ],
            [
                'key'   => 'verify_account_email',
                'value' => '1'
            ],
            [
                'key'   => SlugHelper::getPermalinkSettingKey(Post::class),
                'value' => 'news',
            ],
            [
                'key'   => SlugHelper::getPermalinkSettingKey(Category::class),
                'value' => 'news',
            ],
            [
                'key'   => SlugHelper::getPermalinkSettingKey(City::class),
                'value' => 'city',
            ],
            [
                'key'   => config('plugins.real-estate.real-estate.prefix') . 'review_enabled',
                'value' => "1",
            ],
            [
                'key'   => config('plugins.real-estate.real-estate.prefix') . 'review_fields',
                'value' => '[[{"key":"field","value":"service"}],[{"key":"field","value":"value"}],[{"key":"field","value":"location"}],[{"key":"field","value":"cleanliness"}]]',
            ]
        ]);

        Slug::where('reference_type', Post::class)->update(['prefix' => 'news']);
        Slug::where('reference_type', Category::class)->update(['prefix' => 'news']);
        Slug::where('reference_type', City::class)->update(['prefix' => 'city']);
    }
}
