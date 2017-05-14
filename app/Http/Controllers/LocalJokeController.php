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
use App\JokeAttitude;
use App\JokeComment;
use App\Transformer\ImageTransformer;
use App\Transformer\JokeCommentTransformer;
use App\Transformer\JokeTransformer;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class LocalJokeController extends BaseController {

  public function getJokes(Request $request)
  {
    $this->validate($request, [
      //'time' => 'required',
      'page' => 'integer'
    ]);
    try {
      $userId = JWTAuth::parseToken()->authenticate()->id;
    } catch (TokenExpiredException $exception) {
      $userId = 0;
    } catch (TokenInvalidException $exception) {
      $userId = 0;
    }catch (JWTException $exception) {
      $userId = 0;
    }

    $time      = $request->input('time', time());
    $timestamp = date('Y-m-d H:i:s', $time);
    $page      = $request->input('page', 1);

    $jokes = Joke::where('updated_at', '<=', $timestamp)->orderBy('updated_at', 'DESC')->forPage($page, 20)->get();

    if($userId == 0) {
      foreach ($jokes as $joke) {
        $joke->my_attitude = 0;
        $joke->my_collected = 0;
      }
    } else {
      foreach ($jokes as $joke) {
        $attitude = JokeAttitude::where('joke_id', $joke->id)->where('user_id', $userId)->first();
        if($attitude == null) {
          $joke->my_attitude = 0;
          $joke->my_collected = 0;

        } else {
          $joke->my_attitude = $attitude->attitude;
          $joke->my_collected = $attitude->collected;
        }
      }
    }
    return $this->response->collection($jokes, new JokeTransformer);
  }



  public function collect($jokeId)
  {
    $userId   = JWTAuth::parseToken()->authenticate()->id;
    $attitude = JokeAttitude::where('joke_id', $jokeId)->where('user_id', $userId)->first();
    $joke     = Joke::where('id', $jokeId)->first();
    if ($joke == null) {
      return response(['message' => 'Joke don\'t exists'], Response::HTTP_NOT_FOUND);
    }
    if ($attitude == null) {
      $attitude = JokeAttitude::create([
        'joke_id'   => $jokeId,
        'user_id'   => $userId,
        'collected' => true
      ]);
      if ($attitude != null) {
        $joke->collect_amount++;
        $joke->save();
      } else {
        return response(['message' => 'fail'], Response::HTTP_INTERNAL_SERVER_ERROR);
      }

    } else {
      $attitude->collected = !($attitude->collected);
      $attitude->save();
      $joke->collect_amount = $attitude->collected ? $joke->collect_amount + 1 : $joke->collect_amount - 1;
      $joke->save();
    }

    return response(['message' => 'success'], Response::HTTP_ACCEPTED);
  }

  public function up($jokeId)
  {
    $userId   = JWTAuth::parseToken()->authenticate()->id;
    $attitude = JokeAttitude::where('joke_id', $jokeId)->where('user_id', $userId)->first();
    $joke     = Joke::where('id', $jokeId)->first();
    if ($joke == null) {
      return response(['message' => 'Joke does not exist'], Response::HTTP_NOT_FOUND);
    }
    if ($attitude == null) {
      $attitude = JokeAttitude::create([
        'joke_id'  => $jokeId,
        'user_id'  => $userId,
        'attitude' => 1
      ]);
      if ($attitude != null) {
        $joke->up_amount++;
        $joke->save();
      } else {
        return response(['message' => 'fail'], Response::HTTP_INTERNAL_SERVER_ERROR);
      }

    } else {
      switch ($attitude->attitude) {
        case 1:
          return response(['message' => '已经顶过了'], Response::HTTP_BAD_REQUEST);
          break;

        case -1:
          $attitude->attitude = 1;
          $attitude->save();
          $joke->up_amount   = $joke->up_amount + 1;
          $joke->down_amount = $joke->down_amount - 1;
          $joke->save();
          return response(['message' => '转换一个'], Response::HTTP_ACCEPTED);
          break;
        case 0:
        default:
          $attitude->attitude = 1;
          $attitude->save();
          $joke->up_amount = $joke->up_amount + 1;
          $joke->save();
      }
    }

    return response(['message' => '增加一个'], Response::HTTP_ACCEPTED);
  }

  public function down($jokeId)
  {
    $userId   = JWTAuth::parseToken()->authenticate()->id;
    $attitude = JokeAttitude::where('joke_id', $jokeId)->where('user_id', $userId)->first();
    $joke     = Joke::where('id', $jokeId)->first();
    if ($joke == null) {
      return response(['message' => 'Joke don\'t exists'], Response::HTTP_NOT_FOUND);
    }
    if ($attitude == null) {
      $attitude = JokeAttitude::create([
        'joke_id'  => $jokeId,
        'user_id'  => $userId,
        'attitude' => -1
      ]);
      if ($attitude != null) {
        $joke->down_amount++;
        $joke->save();
      } else {
        return response(['message' => 'fail'], Response::HTTP_INTERNAL_SERVER_ERROR);
      }

    } else {
      switch ($attitude->attitude) {
        case -1:
          return response(['message' => '已经踩过了'], Response::HTTP_BAD_REQUEST);
          break;

        case 1:
          $attitude->attitude = -1;
          $attitude->save();
          $joke->up_amount   = $joke->up_amount - 1;
          $joke->down_amount = $joke->down_amount + 1;
          $joke->save();
          return response(['message' => '转换一个'], Response::HTTP_ACCEPTED);
          break;
        case 0:
        default:
          $attitude->attitude = -1;
          $attitude->save();
          $joke->down_amount = $joke->down_amount + 1;
          $joke->save();
      }
    }

    return response(['message' => '增加一个'], Response::HTTP_ACCEPTED);
  }

  public function comment(Request $request, $jokeId)
  {
    $this->validate($request, [
      'comment'  => 'required',
      'reply_id' => 'integer'
    ]);
    $userId  = JWTAuth::parseToken()->authenticate()->id;
    $comment = $request->input('comment');
    $joke    = Joke::where('id', $jokeId)->first();
    if ($joke == null) {
      return response(['message' => '笑话不存在'], Response::HTTP_NOT_FOUND);
    }
    $replyId = $request->input('reply_id', 0);
    if ($replyId != 0 && JokeComment::where('id', $replyId)->get()->isEmpty()) {
      return response(['message' => '被回复的评论不存在'], Response::HTTP_NOT_FOUND);
    }

    $comment = JokeComment::create([
      'joke_id'  => $jokeId,
      'user_id'  => $userId,
      'comment'  => $comment,
      'reply_id' => $replyId
    ]);
    if ($comment == null) {
      return response(['message' => '评论失败'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    $joke->comment_amount++;
    $joke->save();
    return $this->response->item($comment, new JokeCommentTransformer)->setStatusCode(Response::HTTP_CREATED)->addMeta('message', 'success');
    //return response(['message' => '评论成功'], Response::HTTP_CREATED);
  }
}