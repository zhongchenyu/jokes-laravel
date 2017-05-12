<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/5/12
 * Time: 17:21
 */

namespace App\Http\Controllers;

use App\Transformer\NoteTransformer;
//use Dingo\Api\Contract\Http\Request;
use JWTAuth;
use App\Note;
use Illuminate\Http\Request;
//use Laravel\Lumen\Routing\Controller;

class NoteController extends BaseController {

  public function index(Request $request)
  {
    $user_id = JWTAuth::parseToken()->authenticate()->id;
    $notes   = Note::where('user_id', $user_id)->get();
    return $this->response->collection($notes, new NoteTransformer);
  }

  public function create(Request $request) {
    $this->validate($request, [
      'content' => 'required'
    ]);

    $user_id = JWTAuth::parseToken()->authenticate()->id;
    $params['content'] = $request->input('content', "");
    $params['user_id'] = $user_id;
    $note = Note::create($params);

    return $this->response->item($note, new NoteTransformer);
  }
}