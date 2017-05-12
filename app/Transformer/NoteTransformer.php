<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/5/12
 * Time: 17:34
 */

namespace App\Transformer;

use League\Fractal\TransformerAbstract;
use App\Note;
class NoteTransformer extends TransformerAbstract {
  public function transform(Note $note)
  {
    return [
      'id'      => $note->id,
      'content' => $note->content
    ];
  }
}