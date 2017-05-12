<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/5/12
 * Time: 18:02
 */

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class BaseController extends Controller
{
  use Helpers, AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}