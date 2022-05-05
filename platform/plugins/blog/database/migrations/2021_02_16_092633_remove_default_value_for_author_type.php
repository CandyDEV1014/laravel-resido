<?php

use Botble\ACL\Models\User;
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
        if (!Schema::hasColumn('categories', 'author_type')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('author_type', 255);
            });
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->string('author_type', 255)->change();
        });

        if (!Schema::hasColumn('tags', 'author_type')) {
            Schema::table('tags', function (Blueprint $table) {
                $table->string('author_type', 255);
            });
        }

        Schema::table('tags', function (Blueprint $table) {
            $table->string('author_type', 255)->change();
        });

        if (!Schema::hasColumn('posts', 'author_type')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('author_type', 255);
            });
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->string('author_type', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('author_type', 255)->default(addslashes(User::class))->change();
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->string('author_type', 255)->default(addslashes(User::class))->change();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('author_type', 255)->default(addslashes(User::class))->change();
        });
    }
};
