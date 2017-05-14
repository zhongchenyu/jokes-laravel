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

  public function user() {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function reply() {
    return $this->belongsTo(JokeComment::class, 'reply_id', 'id');
  }
}
