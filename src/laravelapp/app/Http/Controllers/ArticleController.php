<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\CommentRequest;
use App\ArticleTag;
use App\Article_tag;
use App\Article;
use App\Comment;
use App\Reply;
use App\User;
use App\Tag;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index(Request $request)
    { 
        $keywords_array = $request->input('keyword');
        if($keywords_array === null){
            
            $keywords_array = [];
        }
        $keywords = implode(" ",$keywords_array);
        $article_list = [];

        /*検索フォームからタグのリクエストがあるか判定,
        無ければ新着記事を表示する*/
        if($keywords === "") {  
           $article_list = Article::with('user','tags','likes','comments')
           ->ArticleOpen()
           ->Created_atDescPaginate();
           $keyword = '新着記事一覧';
          
           return [
                'article_list' => $article_list,
                'search_keyword' => $keyword,
                'keywords' => $keywords,
           ];
        }
         
        // 検索ワードからtag:の後に続く情報を抽出
        $tag_extract = preg_grep('/^tag:/',$keywords_array);

        /**
         * 
         * タグキーワードが入力されたか判定
         * 無ければ$tag_numberにnullを代入
         * 
         * タグキーワードに該当する記事があればarticle_listに代入
         * 無ければ$article_listに[]を代入
         */
        if(!empty($tag_extract)) {  
            
            $tag_keyword = 
            str_replace('tag:',"",$tag_extract[0]);
            $tag_number = 
            Tag::where('name',$tag_keyword)
            ->first('id');
            
            if($tag_number !== null){

                $article_list = Tag::find($tag_number->id)
                ->articles->sortByDesc('created_at');
           
            }else{
                $article_list = [];
            }
            
        }else{ 
            $tag_number = null; 
        }
        
        /**
         * tag検索ワードとフリーキーワードがどちらもあるか判定
         * 
         * どちらもあった場合タグ検索から取り出したレコードを
         * フリーキーワードで絞り込む
         * 
         * article_listに絞り込んだ結果を代入する
         */
       
        if($tag_number !== null && $keywords !== $tag_extract[0] ) {   
            // 検索ワードからタグの情報を取り除く
            $keyword = str_replace('tag:',"",$keywords);
            $keyword = str_replace($tag_keyword." ","",$keyword); 
           
            $articles = $article_list->filter(
            function($article) use ($keyword)
                {
                    return strpos($article->title,$keyword) !==false;
                });
                
            $articles = $article_list->filter(
            function($article) use ($keyword)
                {
                 return strpos($article->body,$keyword) !== false;
                });

                /*キーワードに該当する記事があったか判定*/
           if( $articles->isEmpty() == false ) {
                   foreach($articles as $article )
                   {
                      $get_article_list[] = $article->id;
                   }
                   /*記事情報と紐付けられたユーザー情報取得*/
                   $article_list = Article::with('user','tags','likes','comments')
                   ->ArticleOpen()
                   ->whereIn  ('id',$get_article_list)
                   ->Created_atDescPaginate();
                   
                   $search_keyword = $keywords.'の検索結果';
             }else{
                    $article_list = [];
                    $search_keyword = $keywords.
                    'に一致する記事はありませんでした';
                
                    return [
                        'article_list' => $article_list,
                        'search_keyword' => $search_keyword,
                     ];
             }
        }else{
            $tag_extract[] = 0; 
        }

        /**タグ検索のみの時*/
        if($keywords === $tag_extract[0] &&
           $tag_number !== null) {
            foreach($article_list as $article )
              {
                $get_article_list[] = $article->id;
              }

           /*記事情報と紐付けられたユーザー情報取得*/
           $article_list = Article::with('user','tags','likes','comments')
           ->ArticleOpen()
           ->whereIn('id',$get_article_list)               
           ->Created_atDescPaginate();
        }


        /**
         * フリーキーワードのみの検索の場合
         */
        if($article_list === [] && $keywords !== "") {   
            
            $articles = Article::get()->filter(
            function($article) use ($keywords) {
                    return strpos($article->title,$keywords) !== false;
                });
                
            $articles = Article::get()->filter(
            function($article) use ($keywords){
                 return strpos($article->body,$keywords)
                  !== false;
                });

                /**該当する記事があるか判定*/
                if( $articles->isEmpty() === false ) {
                   foreach($articles as $article )
                   {
                      $get_article_list[] = $article->id;
                   }
                   /*記事情報と紐付けられたユーザー情報取得*/
                   $article_list = Article::with('user','tags','likes','comments')
                    ->ArticleOpen()
                    ->whereIn('id',$get_article_list)
                    ->Created_atDescPaginate();
                   
                   $search_keyword = $keywords.'の検索結果';
                }else{
                    $article_list = [];
                    $search_keyword = $keywords.
                    'に一致する記事はありませんでした';
        
                    return [
                        'article_list' => $article_list,
                        'search_keyword' => $search_keyword,   
                     ];
                }
        } 
                 
            $search_keyword = $keywords . 'の検索結果';

             return [
                 'search_keyword' => $search_keyword, 
                 'article_list' => $article_list,
                 'keywords' => $keywords,
             ];
    }
                

    public function create()
    {   
        $s3_profile_image = User::GetAuthUserImage();

        return view('article.create',[
            's3_profile_image' => $s3_profile_image,
        ])->with('user',Auth::user());;
    }

    public function store(ArticleRequest $request)
    {  
        /* #(ハッシュタグ)で始まる単語を取得。結果は、$matchに多次元配列で代入される。
        */
        preg_match_all('/#([a-zA-z0-9０-９ぁ-んァ-ヶ亜-熙]+)/u', $request->tags, $match);

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
        return redirect()->route('index');
    }
   
    public function show(Request $request, int $id)
    {   
        $comments = Comment::with(['user','replies','replies.user'])->where('article_id',$id)->get();       

        $article = Article::with('User','tags')
        ->find($id);
        $article_parse = new Article;
        $article_parse_body = $article->parse($article_parse);
      
            return [
                 'article' => $article,
                 'comments' => $comments,
                 'user_id' => Auth::id(),
                 'article_parse_body' => $article_parse_body,
            ];
        
    }

    /**記事の公開、非公開を切り替える */
    public function status(Request $request)
    {
       $article = Article::find($request->article_id);
     
       if($article->status === Article::OPEN ) {
            $article->status = Article::CLOSED;
            $article->save();
        }else {
            $article->status = Article::OPEN;
            $article->save();
        }
        
        return redirect()->back();
    }

    public function comment(CommentRequest $request)
    {   
        Comment::create([
            'body' => $request->body,
            'user_id' => $request->user_id,
            'article_id' => $request->article_id,
        ]);

        return redirect()->back();
    }
    public function commentDelete(Request $request)
    {
        $comment = Comment::where('id',$request->comment_id)->first();
        
        $comment->delete();

        return redirect()->back();
    }

    public function reply(CommentRequest $request)
    { 
        Reply::create([
            'body' => $request->body, 
            'user_id' => Auth::id(),
            'comment_id' => $request->comment_id,
        ]);
        return redirect()->back();
    }
    public function replyDelete(Request $request)
    {
        $reply = Reply::where('id',$request->reply_id)->first();
        $reply->delete();

        return redirect()->back();
    }

   
    public function edit(int $article_id)
    {   
        $tag_list = Article::find($article_id)->tags->pluck('name');
        $tags = $tag_list->toArray();
        $tags_string = implode(" #",$tags);

        $article_data = Article::find($article_id);
      
        $article_parse = new Article;
        $article_parse_body = $article_data->parse($article_parse);

        return [
           $article_parse_body,
           $article_data,
           $tags_string,
        ];
    }
    public function update(ArticleRequest $request)
    {
        $article_id = $request->article_id;
        /* #(ハッシュタグ)で始まる単語を取得。結果は、$matchに多次元配列で代入される。
        */
        preg_match_all('/#([a-zA-z0-9０-９ぁ-んァ-ヶ亜-熙]+)/u', $request->tags, $match);

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

        /** 投稿にタグ付するために、attachメソッドをつかい、モデルを結びつけている中間テーブルにレコードを挿入します。 */

        $article = Article::find($article_id);
        $article->title = request('title');
        $article->body = request('body');
    DB::transaction(function () use ($article,$tags_id)
    {
        $article->save();
        $article->tags()->attach($tags_id);
    });

        return redirect(route('article.show',[
            'articleId' => $article->id,
        ]));
    }

    public function destroy(Request $request)
    {  
        $article = Article::find($request->article_id);
        $article->delete();

        return redirect(route('index'))
        ->with('success','記事を削除しました');
    }
}
