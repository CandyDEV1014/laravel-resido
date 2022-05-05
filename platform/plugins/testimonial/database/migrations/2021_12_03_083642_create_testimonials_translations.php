<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('testimonials_translations')) {
            Schema::create('testimonials_translations', function (Blueprint $table) {
                $table->string('lang_code');
                $table->integer('testimonials_id');
                $table->string('name', 255)->nullable();
                $table->text('content')->nullable();
                $table->string('company', 120)->nullable();

                $table->primary(['lang_code', 'testimonials_id'], 'testimonials_translations_primary');
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
        Schema::dropIfExists('testimonials_translations');
    }
};
