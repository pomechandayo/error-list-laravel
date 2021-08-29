<?php

namespace App\UseCase\Article;

use App\Reply;

final class ReplyUseCase
{
  public function createReply($request,$auth_id) :void
  {
    Reply::create([
            'body' => $request->body, 
            'user_id' => $auth_id,
            'comment_id' => $request->comment_id,
        ]);
  }

  public function deleteReply($request) :void
  {
      $reply = Reply::where('id', $request->reply_id)->first();
      $reply->delete();
  }
}