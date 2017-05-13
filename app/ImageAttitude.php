<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageAttitude extends Model
{
  protected $fillable = [
    'image_id',
    'user_id',
    'attitude',
    'collected'
  ];

  protected $hidden = [
    'updated_at',
    'created_at'
  ];
}
