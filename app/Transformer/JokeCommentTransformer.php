<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/5/13
 * Time: 18:29
 */

namespace App\Transformer;


use App\JokeComment;
use League\Fractal\TransformerAbstract;

class JokeCommentTransformer extends TransformerAbstract {
  public function transform(JokeComment $comment)
  {
    return [
      "id"       => $comment->id,
      "joke_id"  => $comment->joke_id,
      "user_id"  => $comment->user_id,
      "comment"  => $comment->comment,
      "reply_id" => $comment->reply_id,

    ];
  }
}