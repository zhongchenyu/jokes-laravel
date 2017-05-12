<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJokesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::create('jokes', function (Blueprint $table) {
      $table->increments('id');
      $table->text("content", 512);
      $table->string("hashId");
      $table->string("origin_unix_time");
      $table->string("origin_update_time", 64);
      $table->unsignedTinyInteger('status')->default(0);
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
    Schema::dropIfExists('test_jokes');
  }
}
