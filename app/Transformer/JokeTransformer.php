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
      'id'         => $joke->id,
      'content'    => $joke->content,
      'hashId'     => $joke->hashId,
      'updatetime' => strtotime($joke->updated_at)
    ];
  }
}