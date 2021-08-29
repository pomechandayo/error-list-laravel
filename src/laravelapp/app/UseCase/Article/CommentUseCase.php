<?php

namespace App\UseCase\Article;

use App\Comment;

final class CommentUseCase
{
    public function createComment($request)
    {
      Comment::create([
        'body' => $request->body,
        'user_id' => $request->user_id,
        'article_id' => $request->article_id,
      ]);
    }

    public function deleteComment($request)
    {
        $comment = Comment::where('id', $request->comment_id)->first();
        
        $comment->delete();
    }
}