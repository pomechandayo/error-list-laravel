<?php

namespace App\Article;

use App\Tag;
use App\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class TagArticleSaveUseCase
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

  public function articleSave($request) :void
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
}