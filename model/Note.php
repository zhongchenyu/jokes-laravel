<?php

/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/5/12
 * Time: 16:59
 */

use Illuminate\Database\Eloquent\Model;
class Note extends Model{

  protected $fillable = [
    'user_id',
    'content',
  ];

  protected $hidden = [
    'updated_at',
    'created_at'
  ];
}