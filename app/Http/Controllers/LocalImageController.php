<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/5/13
 * Time: 20:46
 */

namespace App\Http\Controllers;

use App\Image;
use App\ImageAttitude;
use App\ImageComment;
use App\Transformer\ImageTransformer;
use App\Transformer\ImageCommentTransformer;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
class LocalImageController extends BaseController{
  public function getImages(Request $request)
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

    $images = Image::where('updated_at', '<=', $timestamp)->orderBy('updated_at', 'DESC')->forPage($page, 20)->get();
    if($userId == 0) {
      foreach ($images as $image) {
        $image->my_attitude = 0;
        $image->my_collected = 0;
      }
    } else {
      foreach ($images as $image) {
        $attitude = ImageAttitude::where('image_id', $image->id)->where('user_id', $userId)->first();
        if($attitude == null) {
          $image->my_attitude = 0;
          $image->my_collected = 0;

        } else {
          $image->my_attitude = $attitude->attitude;
          $image->my_collected = $attitude->collected;
        }
      }
    }
    return $this->response->collection($images, new ImageTransformer);
  }

  public function collect($imageId)
  {
    $userId   = JWTAuth::parseToken()->authenticate()->id;
    $attitude = ImageAttitude::where('image_id', $imageId)->where('user_id', $userId)->first();
    $image     = Image::where('id', $imageId)->first();
    if ($image == null) {
      return response(['message' => 'Image don\'t exists'], Response::HTTP_NOT_FOUND);
    }
    if ($attitude == null) {
      $attitude = ImageAttitude::create([
        'image_id'   => $imageId,
        'user_id'   => $userId,
        'collected' => true
      ]);
      if ($attitude != null) {
        $image->collect_amount++;
        $image->save();
      } else {
        return response(['message' => 'fail'], Response::HTTP_INTERNAL_SERVER_ERROR);
      }

    } else {
      $attitude->collected = !($attitude->collected);
      $attitude->save();
      $image->collect_amount = $attitude->collected ? $image->collect_amount + 1 : $image->collect_amount - 1;
      $image->save();
    }

    return response(['message' => 'success'], Response::HTTP_ACCEPTED);
  }

  public function up($imageId)
  {
    $userId   = JWTAuth::parseToken()->authenticate()->id;
    $attitude = ImageAttitude::where('image_id', $imageId)->where('user_id', $userId)->first();
    $image     = Image::where('id', $imageId)->first();
    if ($image == null) {
      return response(['message' => 'Image don\'t exists'], Response::HTTP_NOT_FOUND);
    }
    if ($attitude == null) {
      $attitude = ImageAttitude::create([
        'image_id'  => $imageId,
        'user_id'  => $userId,
        'attitude' => 1
      ]);
      if ($attitude != null) {
        $image->up_amount++;
        $image->save();
      } else {
        return response(['message' => 'fail'], Response::HTTP_INTERNAL_SERVER_ERROR);
      }

    } else {
      switch ($attitude->attitude) {
        case 1:
          return response(['message' => '已经顶过了']);
          break;

        case -1:
          $attitude->attitude = 1;
          $attitude->save();
          $image->up_amount   = $image->up_amount + 1;
          $image->down_amount = $image->down_amount - 1;
          $image->save();
          break;
        case 0:
        default:
          $attitude->attitude = 1;
          $attitude->save();
          $image->up_amount = $image->up_amount + 1;
          $image->save();
      }
    }

    return response(['message' => 'success'], Response::HTTP_ACCEPTED);
  }

  public function down($imageId)
  {
    $userId   = JWTAuth::parseToken()->authenticate()->id;
    $attitude = ImageAttitude::where('image_id', $imageId)->where('user_id', $userId)->first();
    $image     = Image::where('id', $imageId)->first();
    if ($image == null) {
      return response(['message' => 'Image don\'t exists'], Response::HTTP_NOT_FOUND);
    }
    if ($attitude == null) {
      $attitude = ImageAttitude::create([
        'image_id'  => $imageId,
        'user_id'  => $userId,
        'attitude' => -1
      ]);
      if ($attitude != null) {
        $image->down_amount++;
        $image->save();
      } else {
        return response(['message' => 'fail'], Response::HTTP_INTERNAL_SERVER_ERROR);
      }

    } else {
      switch ($attitude->attitude) {
        case -1:
          return response(['message' => '已经踩过了']);
          break;

        case 1:
          $attitude->attitude = -1;
          $attitude->save();
          $image->up_amount   = $image->up_amount - 1;
          $image->down_amount = $image->down_amount + 1;
          $image->save();
          break;
        case 0:
        default:
          $attitude->attitude = -1;
          $attitude->save();
          $image->down_amount = $image->down_amount + 1;
          $image->save();
      }
    }

    return response(['message' => 'success'], Response::HTTP_ACCEPTED);
  }

  public function comment(Request $request, $imageId)
  {
    $this->validate($request, [
      'comment'  => 'required',
      'reply_id' => 'integer'
    ]);
    $userId  = JWTAuth::parseToken()->authenticate()->id;
    $comment = $request->input('comment');
    $image    = Image::where('id', $imageId)->first();
    if ($image == null) {
      return response(['message' => '笑话不存在'], Response::HTTP_NOT_FOUND);
    }
    $replyId = $request->input('reply_id', 0);
    if ($replyId != 0 && ImageComment::where('id', $replyId)->get()->isEmpty()) {
      return response(['message' => '被回复的评论不存在'], Response::HTTP_NOT_FOUND);
    }

    $comment = ImageComment::create([
      'image_id'  => $imageId,
      'user_id'  => $userId,
      'comment'  => $comment,
      'reply_id' => $replyId
    ]);
    if ($comment == null) {
      return response(['message' => '评论失败'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    $image->comment_amount++;
    $image->save();
    return $this->response->item($comment, new ImageCommentTransformer)->setStatusCode(Response::HTTP_CREATED)->addMeta('message', 'success');
    //return response(['message' => '评论成功'], Response::HTTP_CREATED);
  }
}