<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/4/29
 * Time: 14:05
 */

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
class TestController extends BaseController{
  public function test()
  {
    return 'test';
  }
}