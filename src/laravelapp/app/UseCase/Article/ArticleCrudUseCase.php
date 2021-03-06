<?php

namespace App\UseCase\Article;

use App\Tag;
use App\Article;
use App\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class ArticleCrudUseCase
{

  public function tagSava($tags) :array
  {
      /* #(ハッシュタグ)で始まる単語を取得。結果は、$matchに多次元配列で代入される。
        */
        preg_match_all('/#([a-zA-z0-9０-９ぁ-んァ-ヶ亜-熙]+)/u', $tags, $match);

      /* $match[0]に#(ハッシュタグ)あり、$match[1]に#(ハッシュタグ)なしの結果が入ってくるので、$match[1]で#(ハッシュタグ)なしの結果のみを使います。
        */
        $tags = [];
        foreach ($match[1] as $tag) {
            $record = Tag::firstOrCreate(['name' => $tag]);
            array_push($tags,$record);
            /* firstOrCreateメソッドで、tags_tableのnameカラムに該当のない$tagは新規登録される。
            */
        };

        /*投稿に紐付けされるタグのidを配列化 */
        $tags_id = [];
        foreach ($tags as $tag) {
            array_push($tags_id,$tag['id']);
        };

        return $tags_id;
  }

  public function articleCreate($request) :void
  {
      $tags_id = $this->tagSava($request->tags);

      /** 投稿にタグ付するために、attachメソッドをつかい、モデルを結びつけている中間テーブルにレコードを挿入します。 */
      
      $article = new Article;
      $article->title = $request->title;
      $article->body = $request->body;
      $article->user_id = Auth::user()->id;
      $article->status = Article::OPEN;

      DB::transaction(function() use ($article,$tags_id) {
        $article->save();
        $article->tags()->attach($tags_id);
    });
  }

  public function articleUpdate($request)
  { 
      $tags_id = $this->tagSava($request->tags);
      $article = Article::find($request->article_id);
      
      $article->title = $request->title;
      $article->body = $request->body;

      DB::transaction(function () use ($article,$tags_id)
{
      $article->save();
      $article->tags()->attach($tags_id);
      });

      return $article->id;
  }

    public function showArticle($id,$user_id)
    {
        $comments = Comment::with(['user',  'replies','replies.user'])->where ('article_id',$id)->get();       
      
        $article = Article::with('User','tags')
        ->find($id);
        $article_parse = new Article;
        $article_parse_body = $article->parse ($article_parse);
    
        return [
             'article' => $article,
             'comments' => $comments,
             'user_id' => $user_id,
             'article_parse_body' => $article_parse_body,
        ];
    }

    public function deleteArticle($request)
    {
        $article = Article::find  ($request->article_id);
          $article->delete();

          return redirect(route('index'))
          ->with('success','記事を削除しました');
    }
}