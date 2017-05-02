<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/5/2
 * Time: 17:53
 */
namespace App\Transformer;

use App\User;
use League\Fractal\TransformerAbstract;
class UserTransformer extends TransformerAbstract {
  public function transform(User $user)
  {
    return [
      'id' => $user->id,
      'name' => $user->name,
      'email' => $user->email,
    ];
  }
}