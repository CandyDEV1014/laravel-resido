<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Botble\Location\Models\City;

class UpdateCityTable extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->string('slug', 120)->unique()->nullable();
            $table->integer('state_id')->unsigned()->nullable()->change();
        });

        $cities = City::get();

        foreach ($cities as $city) {
            $city->slug = Str::slug($city->name);
            $city->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn(['slug']);
            $table->integer('state_id')->unsigned()->change();
        });
    }
}
