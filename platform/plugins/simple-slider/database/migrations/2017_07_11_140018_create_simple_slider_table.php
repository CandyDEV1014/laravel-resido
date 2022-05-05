<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSimpleSliderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simple_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('key', 120);
            $table->string('description', 255)->nullable();
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('simple_slider_items', function (Blueprint $table) {
            $table->id();
            $table->integer('simple_slider_id')->unsigned();
            $table->string('title', 255)->nullable();
            $table->string('image', 255);
            $table->string('link', 255)->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simple_sliders');
        Schema::dropIfExists('simple_slider_items');
    }
}
