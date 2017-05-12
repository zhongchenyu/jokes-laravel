<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
  protected $table = 'notes';

  protected $fillable = [
    'user_id',
    'content',
  ];

  protected $hidden = [
    'updated_at',
    'created_at'
  ];
}
