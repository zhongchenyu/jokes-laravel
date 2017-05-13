<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers'], function ($api) {
  $api->get('jokes', 'JokeController@getJokes');  //获取远端服务器笑话列表
  $api->get('pictures', 'JokeController@getFunPic'); //获取远端服务器趣图列表

  $api->get('my_jokes', 'LocalJokeController@getJokes');   //获取本地服务器打的笑话列表
  $api->get('my_images', 'LocalImageController@getImages');  //获取服务器本地的趣图列表
  $api->get('blacklist', 'JokeController@getBlackList');
  $api->get('login', 'Auth\AuthenticateController@authenticate');
  $api->get('test', 'TestController@test');
  $api->post('register', 'Auth\RegisterController@register');

  $api->group(['middleware' => 'jwt.auth', 'providers' => 'jwt'], function ($api) { //
    $api->get('user', 'UserController@getUserInfo');
    $api->get('notices', 'NoticeController@index');
    $api->get('notes', 'NoteController@index');
    $api->post('notes', 'NoteController@create');

    $api->post('my_jokes/{joke_id}/collection', 'LocalJokeController@collect');  //收藏笑话
    $api->post('my_jokes/{joke_id}/up', 'LocalJokeController@up');  //顶笑话
    $api->post('my_jokes/{joke_id}/down', 'LocalJokeController@down');  //踩笑话
    $api->post('my_jokes/{joke_id}/comment', 'LocalJokeController@comment'); //评论笑话

    $api->post('my_images/{joke_id}/collection', 'LocalImageController@collect');  //收藏趣图
    $api->post('my_images/{joke_id}/up', 'LocalImageController@up');  //顶趣图
    $api->post('my_images/{joke_id}/down', 'LocalImageController@down');  //踩趣图
    $api->post('my_images/{joke_id}/comment', 'LocalImageController@comment'); //评论趣图
  });
});

/*
Route::get('apitest', 'TestController@test');

Route::get('jokes', 'JokeController@getJokes');
Route::get('funpic', 'JokeController@getFunPic');
Route::get('blacklist', function () {
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

});
*/