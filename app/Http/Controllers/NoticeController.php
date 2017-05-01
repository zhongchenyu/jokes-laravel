<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/5/2
 * Time: 0:24
 */

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Illuminate\Routing\Controller;
class NoticeController extends Controller{

  public function index()
  {
    return ["content" => "This notice can be seen only after Auth"];
  }
}