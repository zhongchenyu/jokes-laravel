<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/4/29
 * Time: 14:05
 */

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Illuminate\Routing\Controller as BaseController;

class TestController extends BaseController {
  public function test()
  {
    $newUser = [
      'name' => 'new',
      'email' => 'new',
      'password' => 'new',
    ];
    //User::create($newUser);
    $user  = User::first();
    $token = JWTAuth::fromUser($user);
    //$id    = $token->authenticate()->id;
    return [
      'token' => $token,
      //'id'    => $id,
    ];
  }
}