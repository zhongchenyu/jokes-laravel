<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/5/1
 * Time: 11:28
 */

namespace App\Http\Controllers;

use Dingo\Api\Contract\Http\Request;
use JWTAuth;
use App\User;
use Illuminate\Routing\Controller;
class UserController extends Controller{
  public function getUserInfo(Request $request)
  {
    $user = JWTAuth::parseToken()->authenticate();
    return $user;
  }
}