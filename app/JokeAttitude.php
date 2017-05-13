<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JokeAttitude extends Model {
  protected $fillable = [
    'joke_id',
    'user_id',
    'attitude',
    'collected'
  ];

  protected $hidden = [
    'updated_at',
    'created_at'
  ];
}
