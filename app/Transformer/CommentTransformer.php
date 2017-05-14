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
use App\User;
class CommentTransformer extends TransformerAbstract {

  public function __construct()
  {
    //parent::__construct();
    $this->userTransform = new UserTransformer();
  }

  public function transform( $comment)
  {

    return [
      "id"       => $comment->id,
      "user"  => $this->userTransform->transform($comment->user),
      "comment"  => $comment->comment,
      "reply" => $this->replyTransform($comment->reply),
    ];
  }

  private function replyTransform($reply) {
    if(!$reply) {
      return null;
    }

    return [
      'id' => $reply->id,
      'user' => $this->userTransform->transform($reply->user),
      'comment' => $reply->comment
    ];
  }
}