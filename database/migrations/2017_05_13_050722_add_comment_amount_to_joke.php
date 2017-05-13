<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentAmountToJoke extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('jokes', function (Blueprint $table) {
      $table->unsignedInteger('up_amount')->comment('顶数量')->default(0);
      $table->unsignedInteger('down_amount')->comment('踩数量')->default(0);
      $table->unsignedInteger('collect_amount')->comment('收藏数量')->default(0);
      $table->unsignedInteger('comment_amount')->comment('评论数量')->default(0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('jokes', function (Blueprint $table) {
      //
    });
  }
}
