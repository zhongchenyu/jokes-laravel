<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('image_comments', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
        });

      Schema::table('joke_comments', function (Blueprint $table) {
        $table->unsignedInteger('user_id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('image_comments', function (Blueprint $table) {
            //
        });
    }
}
