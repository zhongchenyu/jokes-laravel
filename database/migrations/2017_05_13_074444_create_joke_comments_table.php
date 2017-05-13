<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJokeCommentsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('joke_comments', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('joke_id');
      $table->text('comment');
      $table->unsignedInteger('reply_id')->default(0);
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
    Schema::dropIfExists('joke_comments');
  }
}
