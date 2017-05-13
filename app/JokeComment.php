<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JokeComment extends Model {
  protected $fillable = [
    'joke_id',
    'user_id',
    'comment',
    'reply_id'
  ];

  protected $hidden = [
    'updated_at',
    'created_at'
  ];
}
