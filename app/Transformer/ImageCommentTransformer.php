<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/5/13
 * Time: 21:17
 */

namespace App\Transformer;


use App\ImageComment;
use League\Fractal\TransformerAbstract;

class ImageCommentTransformer extends TransformerAbstract {
  public function transform(ImageComment $comment)
  {
    return [
      "id"       => $comment->id,
      "image_id"  => $comment->image_id,
      "user_id"  => $comment->user_id,
      "comment"  => $comment->comment,
      "reply_id" => $comment->reply_id,

    ];
  }
}