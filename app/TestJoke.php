<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestJoke extends Model {
  protected $fillable = [
    "content",
    "hashId",
    "origin_unix_time",
    "origin_update_time"
  ];

  protected $hidden = [
    'created_at',
    'updated_at'
  ];
}
