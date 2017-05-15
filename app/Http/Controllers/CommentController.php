<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/5/15
 * Time: 0:48
 */

namespace App\Http\Controllers;

use App\Image;
use App\ImageComment;
use App\Joke;
use App\JokeAttitude;
use App\JokeComment;
use App\Transformer\ImageTransformer;
use App\Transformer\CommentTransformer;
use App\Transformer\JokeTransformer;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class CommentController extends BaseController {
  public function createComment(Request $request, $jokeId)
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
    if ($replyId != 0 && JokeComment::where('id', $replyId)->where('joke_id', $jokeId)->get()->isEmpty()) {
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
    return $this->response->item($comment, new CommentTransformer)->setStatusCode(Response::HTTP_CREATED)->addMeta('message', 'success');
    //return response(['message' => '评论成功'], Response::HTTP_CREATED);
  }

  public function indexComment(Request $request, $jokeId)
  {
    if (Joke::find($jokeId) == null) {
      return $this->response->error('笑话不存在', Response::HTTP_NOT_FOUND);
    }
    $page = $request->input('page',1);
    $comments = JokeComment::where('joke_id', $jokeId)->forPage($page, 20)->get();
    return $this->response->collection($comments, new CommentTransformer());
  }

  public function createImageComment(Request $request, $imageId)
  {
    $this->validate($request, [
      'comment'  => 'required',
      'reply_id' => 'integer',
    ]);
    $userId  = JWTAuth::parseToken()->authenticate()->id;
    $comment = $request->input('comment');

    $image   = Image::where('id', $imageId)->first();
    if ($image == null) {
      return response(['message' => '趣图不存在'], Response::HTTP_NOT_FOUND);
    }

    $replyId = $request->input('reply_id', 0);
    if ($replyId != 0 && ImageComment::where('id', $replyId)->where('image_id', $imageId)->get()->isEmpty()) {
      return response(['message' => '被回复的评论不存在'], Response::HTTP_NOT_FOUND);
    }

    $comment = ImageComment::create([
      'image_id' => $imageId,
      'user_id'  => $userId,
      'comment'  => $comment,
      'reply_id' => $replyId
    ]);
    if ($comment == null) {
      return response(['message' => '评论失败'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    $image->comment_amount++;
    $image->save();
    return $this->response->item($comment, new CommentTransformer)->setStatusCode(Response::HTTP_CREATED)->addMeta('message', 'success');
    //return response(['message' => '评论成功'], Response::HTTP_CREATED);
  }

  public function indexImageComment(Request $request, $imageId)
  {
    if (Image::find($imageId) == null) {
      return $this->response->error('趣图不存在', Response::HTTP_NOT_FOUND);
    }
    $page = $request->input('page',1);
    $comments = ImageComment::where('image_id', $imageId)->forPage($page, 20)->get();
    return $this->response->collection($comments, new CommentTransformer());
  }
}