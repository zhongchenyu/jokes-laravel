<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/4/29
 * Time: 14:17
 */

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class JokeController extends BaseController {
  public function getJokes()
  {
    header('Content-type:text/html; charset=utf-8');

    $appKey = '*****APP_KEY******';
    //$appKey = 'error_token';
    $url         = 'http://japi.juhe.cn/joke/content/list.from';
    $params      = array(
      "sort"     => "desc",
      "page"     => $_GET['page'],
      "pagesize" => 20,
      "time"     => time(),
      "key"      => $appKey
    );//dd(date('Y-m-d H:i:s', $params['time']));
    $paramstring = http_build_query($params);


    $uri = $url . '?' . $paramstring;

    $fp = fopen($uri, 'r');
    stream_get_meta_data($fp);
    $result = "";
    while (!feof($fp)) {
      $result .= fgets($fp, 1024);
    }
    echo "$result";
    fclose($fp);
  }
}