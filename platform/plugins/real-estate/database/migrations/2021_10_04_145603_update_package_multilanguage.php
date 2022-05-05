<?php

use Illuminate\Database\Migrations\Migration;
use Botble\RealEstate\Models\Package;
use Botble\Language\Models\LanguageMeta;
use Botble\Language\Models\Language;

class UpdatePackageMultilanguage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $packages = Package::all();
        foreach (Language::get() as $k => $language) {
            foreach ($packages as $index => $package) {
                $found = LanguageMeta::where([
                    'reference_id'   => $package->id,
                    'reference_type' => Package::class,
                    'lang_meta_code' => $language->lang_code
                ])->first();
                if (!$found) {
                    if ($language->lang_code !== 'en_US') {
                        $packageNew = Package::create([
                            'name'               => $package->name,
                            'price'              => $package->price,
                            'currency_id'        => $package->currency_id,
                            'percent_save'       => $package->percent_save,
                            'order'              => $package->order,
                            'number_of_listings' => $package->number_of_listings,
                            'is_default'         => $package->is_default,
                            'features'           => $package->features,
                        ],);
                        LanguageMeta::saveMetaData($packageNew, $language->lang_code, $package->lang_meta_origin);
                    } else {
                        LanguageMeta::saveMetaData($package, $language->lang_code);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
