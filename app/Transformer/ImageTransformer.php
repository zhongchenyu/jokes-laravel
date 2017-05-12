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
      'id'         => $image->id,
      'content'    => $image->content,
      'hashId'     => $image->hashId,
      'url'        => $image->url,
      'updatetime' => strtotime($image->updated_at)
    ];
  }
}