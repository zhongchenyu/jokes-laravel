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

  public function main()
  {
    return "笑话趣图收集下载地址：\n" . "http://a.app.qq.com/o/simple.jsp?pkgname=chenyu.jokes ";
  }
  public function getJokes()
  {
    header('Content-type:text/html; charset=utf-8');

    if(array_key_exists('page', $_GET) ) {
      $page = $_GET['page'];
    } else {
      $page = 1;
    }

    $appKey = env('JUHE_API_KEY');
    //$appKey = 'error_token';
    $url         = 'http://japi.juhe.cn/joke/content/list.from';
    $params      = array(
      "sort"     => "desc",
      "page"     => $page,
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

  public function getFunPic()
  {
    if(array_key_exists('page', $_GET) ) {
      $page = $_GET['page'];
    } else {
      $page = 1;
    }

    header('Content-type:text/html; charset=utf-8');

    $appKey = env('JUHE_API_KEY');

    $url = 'http://japi.juhe.cn/joke/img/list.from';
    $params = array(
      "sort" => "desc",
      "page" => $page,
      "pagesize" => 20,
      "time" => time(),
      "key" => $appKey
    );
    $paramstring = http_build_query($params);


    $uri = $url.'?'.$paramstring;

    $fp = fopen($uri, 'r');
    stream_get_meta_data($fp);
    $result = "";
    while(!feof($fp)) {
      $result .= fgets($fp, 1024);
    }
    echo "$result";
    fclose($fp);

  }

  public function getBlackList()
  {
    return ('
  {
  "error_code": 0,
  "reason": "Success",
  "result": {
    "data":
[
{"hashId":"9E2DE9CAB03E41794E64ADA9F68FE86C"},
{"hashId":"EED5B680E63D97A38DD8C897AB45194D"},
{"hashId":"AAAC331460A3097ADB933EFC5DEFD804"},
{"hashId":"0D6B2969C319C1CFEB5A3BA188B7B4E1"},
{"hashId":"E5564119033B2BBBE729527902ED3873"},
{"hashId":"CEAD8E7BAAB3367382DDB1F2FD5AC1BD"},
{"hashId":"7C9E45A45CB36D60CFC70D5F5E4F390B"},
{"hashId":"02C286DCB387A88D3438C528DCCB72AF"},
{"hashId":"0D6B2969C319C1CFEB5A3BA188B7B4E1"}
]
}
}

  ');
  }

  public function info() {
    echo date('Y-m-d H:i:s'). '<br>';
    echo phpinfo();
  }
}