<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/5/13
 * Time: 2:45
 */

namespace App\Transformer;


use App\Image;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract {
  public function transform(Image $image)
  {
    return [
      'id'             => $image->id,
      'content'        => $image->content,
      'hashId'         => $image->hashId,
      'url'            => $image->url,
      'updatetime'     => strtotime($image->updated_at),
      'up_amount'      => $image->up_amount,
      'down_amount'    => $image->down_amount,
      'collect_amount' => $image->collect_amount,
      'comment_amount' => $image->comment_amount,
      'my_attitude'    => $image->my_attitude,
      'my_collected'   => $image->my_collected
    ];
  }
}