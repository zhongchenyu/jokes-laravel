<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/5/13
 * Time: 1:11
 */

namespace App\Http\Controllers;

use App\Image;
use App\Joke;
use App\Transformer\ImageTransformer;
use App\Transformer\JokeTransformer;
use Illuminate\Http\Request;
use JWTAuth;

class LocalJokeController extends BaseController {

  public function getJokes(Request $request)
  {
    $this->validate($request, [
      //'time' => 'required',
      'page' => 'integer'
    ]);

    $time      = $request->input('time', time());
    $timestamp = date('Y-m-d H:i:s', $time);
    $page      = $request->input('page', 1);

    $jokes = Joke::where('updated_at', '<=', $timestamp)->orderBy('updated_at', 'DESC')->forPage($page, 20)->get();

    return $this->response->collection($jokes, new JokeTransformer);
  }

public function getImages(Request $request) {
  $this->validate($request, [
    //'time' => 'required',
    'page' => 'integer'
  ]);

  $time      = $request->input('time', time());
  $timestamp = date('Y-m-d H:i:s', $time);
  $page      = $request->input('page', 1);

  $images = Image::where('updated_at', '<=', $timestamp)->orderBy('updated_at', 'DESC')->forPage($page, 20)->get();

  return $this->response->collection($images, new ImageTransformer);
}
}