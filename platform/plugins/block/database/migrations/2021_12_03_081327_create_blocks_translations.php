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
        if (!Schema::hasTable('blocks_translations')) {
            Schema::create('blocks_translations', function (Blueprint $table) {
                $table->string('lang_code');
                $table->integer('blocks_id');
                $table->string('name', 255)->nullable();
                $table->string('description', 255)->nullable();
                $table->longText('content')->nullable();

                $table->primary(['lang_code', 'blocks_id'], 'blocks_translations_primary');
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
        Schema::dropIfExists('blocks_translations');
    }
};
