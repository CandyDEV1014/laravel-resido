<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRealEstateTranslationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('re_properties_translations')) {
            Schema::create('re_properties_translations', function (Blueprint $table) {
                $table->string('lang_code');
                $table->integer('re_properties_id');
                $table->string('name', 255)->nullable();
                $table->string('description', 400)->nullable();
                $table->longText('content')->nullable();
                $table->string('location', 255)->nullable();

                $table->primary(['lang_code', 're_properties_id'], 're_properties_translations_primary');
            });
        }

        if (!Schema::hasTable('re_property_types_translations')) {
            Schema::create('re_property_types_translations', function (Blueprint $table) {
                $table->string('lang_code');
                $table->integer('re_property_types_id');
                $table->string('name', 255)->nullable();
                $table->string('slug', 60);
                $table->primary(['lang_code', 're_property_types_id'], 're_features_translations_primary');
            });
        }

        if (!Schema::hasTable('re_features_translations')) {
            Schema::create('re_features_translations', function (Blueprint $table) {
                $table->string('lang_code');
                $table->integer('re_features_id');
                $table->string('name', 255)->nullable();

                $table->primary(['lang_code', 're_features_id'], 're_features_translations_primary');
            });
        }

        if (!Schema::hasTable('re_facilities_translations')) {
            Schema::create('re_facilities_translations', function (Blueprint $table) {
                $table->string('lang_code');
                $table->integer('re_facilities_id');
                $table->string('name', 255)->nullable();

                $table->primary(['lang_code', 're_facilities_id'], 're_facilities_translations_primary');
            });
        }

        if (!Schema::hasTable('re_categories_translations')) {
            Schema::create('re_categories_translations', function (Blueprint $table) {
                $table->string('lang_code');
                $table->integer('re_categories_id');
                $table->string('name', 255)->nullable();
                $table->string('description', 400)->nullable();

                $table->primary(['lang_code', 're_categories_id'], 're_categories_translations_primary');
            });
        }

        if (!Schema::hasTable('re_packages_translations')) {
            Schema::create('re_packages_translations', function (Blueprint $table) {
                $table->string('lang_code');
                $table->integer('re_packages_id');
                $table->string('name', 120)->nullable();
                $table->text('features')->nullable();
                $table->primary(['lang_code', 're_packages_id'], 're_packages_translations_primary');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('re_properties_translations');
        Schema::dropIfExists('re_features_translations');
        Schema::dropIfExists('re_facilities_translations');
        Schema::dropIfExists('re_categories_translations');
    }
}
