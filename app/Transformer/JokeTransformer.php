<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/5/13
 * Time: 1:52
 */

namespace App\Transformer;

use App\Joke;
use League\Fractal\TransformerAbstract;

class JokeTransformer extends TransformerAbstract {
  public function transform(Joke $joke)
  {
    return [
      'id'             => $joke->id,
      'content'        => $joke->content,
      'hashId'         => $joke->hashId,
      'updatetime'     => strtotime($joke->created_at),
      'up_amount'      => $joke->up_amount,
      'down_amount'    => $joke->down_amount,
      'collect_amount' => $joke->collect_amount,
      'comment_amount' => $joke->comment_amount,
      'my_attitude'    => $joke->my_attitude,
      'my_collected'   => $joke->my_collected
    ];
  }
}