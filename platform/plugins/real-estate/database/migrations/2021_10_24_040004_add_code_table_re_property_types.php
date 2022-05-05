<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Botble\RealEstate\Models\Type;

class AddCodeTableRePropertyTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('re_property_types', function (Blueprint $table) {
            $table->string('code', 60)->nullable();
        });
        $types = Type::get();
        foreach ($types as $type) {
            $type->code = $type->slug;
            $type->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('re_property_types', function (Blueprint $table) {
            $table->dropColumn(['code']);
        });
    }
}
