<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model {
  protected $fillable = [
    'content',
    'hashId',
    'origin_unix_time',
    'origin_update_time',
    'url',
    "up_amount",
    "down_amount",
    "collect_amount",
    "comment_amount",
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
    'status'
  ];
}
