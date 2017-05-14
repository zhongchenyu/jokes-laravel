<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageComment extends Model
{
  protected $fillable = [
    'image_id',
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

  public function reply()
  {
    return $this->belongsTo(ImageComment::class, 'reply_id', 'id');
  }
}
