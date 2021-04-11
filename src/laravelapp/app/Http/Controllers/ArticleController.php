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
use App\Like;
use App\Tag;

class ArticleController extends Controller
{
    public function index(User $user,Request $request)
    {   
        $keywords_array = $request->input('keyword');
        $keywords = implode(" ",$keywords_array);
        $article_list = [];
              
        /*検索フォームからタグのリクエストがあるか判定,
        無ければ新着記事を表示する*/
        if($keywords === "")
         {  
           $article_list = Article::with('User')
            ->ArticleOpen()
            ->Created_atDescPaginate();
        
           $keyword = '新着記事一覧';

             return view('index',[
                'article_list' => $article_list,
                'keyword' => $keyword,
                'keywords' => $keywords,
               ])->with('user',Auth::user());
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
        if(!empty($tag_extract))
        {  
            $tag_keyword = str_replace('tag:',"",$tag_extract[0]);
            $tag_number = Tag::where('name',$tag_keyword)
            ->first('id');
            
            if($tag_number !== null)
            {
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
       
        if($tag_number !== null && $keywords !== $tag_extract[0] )
        {   
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

           if( $articles->isEmpty() == false )
                {
                   foreach($articles as $article )
                   {
                      $get_article_list[] = $article->id;
                   }
                   /*記事情報と紐付けられたユーザー情報取得*/
                   $article_list = Article::with('User')
                   ->ArticleOpen()
                   ->whereIn  ('id',$get_article_list)
                   ->Created_atDescPaginate();
                   
                   $search_keyword = $keywords.'の検索結果';
             }else{
                    $article_list = [];
                    $search_keyword = $keywords.
                    'に一致する記事はありませんでした';
                
                    return view('index',[
                        'article_list' => $article_list,
                        'keyword' => $search_keyword,
                     ])->with('user',Auth::user());
             }
        }else{
            $tag_extract[] = 0; 
        }

        /**
         * タグ検索のみの時 
         * 
        */
        if($keywords === $tag_extract[0] &&
           $tag_number !== null)
        {
            foreach($article_list as $article )
              {
                $get_article_list[] = $article->id;
              }

           /*記事情報と紐付けられたユーザー情報取得*/
           $article_list = Article::with('User')
           ->ArticleOpen()
           ->whereIn('id',$get_article_list)               
           ->Created_atDescPaginate();
        }


        /**
         * フリーキーワードのみの検索の場合
         */
        if($article_list === [] && $keywords !== "")
        {   
            $articles = Article::get()->filter(
            function($article) use ($keywords)
                {
                    return strpos($article->title,$keywords) !==false;
                });
                
            $articles = Article::get()->filter(
            function($article) use ($keywords)
                {
                 return strpos($article->body,$keywords)
                  !== false;
                });


                if( $articles->isEmpty() == false )
                {
                   foreach($articles as $article )
                   {
                      $get_article_list[] = $article->id;
                   }
                   /*記事情報と紐付けられたユーザー情報取得*/
                   $article_list = Article::with('User')
                    ->ArticleOpen()
                    ->whereIn('id',$get_article_list)
                    ->Created_atDescPaginate();
                   
                   $search_keyword = $keywords.'の検索結果';
                }else{
                    $article_list = [];
                    $search_keyword = $keywords.
                    'に一致する記事はありませんでした';
        
                    return view('index',[
                        'article_list' => $article_list,
                        'keyword' => $search_keyword,
                     ])->with('user',Auth::user());
                }
        }
               
                 
                $search_keyword = $keywords . 'の検索結果';
                 

                 return view('index',[
                    'search_keyword' => $search_keyword, 
                    'article_list' => $article_list,
                    'keywords' => $keywords,
                ])->with('user',Auth::user());
    }
                

    public function create()
    {   
        return view('article.create')
        ->with('user',Auth::user());;
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
        
        $post = new Article;
        $post->title = $request->title;
        $post->body = $request->body;
        $post->user_id = Auth::user()->id;
        $post->status = Article::OPEN;
        $post->save();
        $post->tags()->attach($tags_id);
        
        return redirect()->route('index');

    }
  
    public function show(Request $request, int $id)
    {   

        $comments = Comment::with(['user','replies','replies.user'])->where('article_id',$id)->get();
        
        $article = Article::with('User')
        ->find($id);
        $article_parse = new Article;
        $article_parse_body = $article->parse($article_parse);

        if($article->status === Article::CLOSED)
        {
            return redirect()->route('index');
        }else{
            return view('article.show',[
                 'article' => $article,
                 'comments' => $comments,
                 'article_parse_body' => $article_parse_body
             ])->with('user',Auth::user());
        }
    }

    public function status(Request $request)
    {
       $article = Article::find($request->article_id);
     
       if($article->status === Article::OPEN )
        {
            $article->status = Article::CLOSED;
            $article->save();
        }else
        {
            $article->status = Article::OPEN;
            $article->save();
        }
        
        return redirect()->action('ArticleController@show',
         $request->article_id); 
    }

    public function comment(CommentRequest $request)
    {   
        $comment = new Comment;
        $comment->article_id = $request->article_id;
        $comment->user_id = $request->user_id;
        $comment->body = $request->body;
        $comment->save();

        return redirect()->back();
    }
    public function comment_delete(int $id)
    {
        $comment = Comment::where('id',$id)->first();
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
    public function reply_delete(int $id)
    {
        $reply = Reply::where('id',$id)->first();
        $reply->delete();

        return redirect()->back();
    }

    public function like(int $id)
    {
        Like::create([
            'article_id' => $id,
            'user_id' => Auth::id(),
        ]);

        session()
        ->flash('success','You Liked the Article');

        return redirect()->back();
    }
    public function unlike(int $id)
    {
        $like = Like::where('article_id',$id)
        ->where('user_id',Auth::id())
        ->first();
        $like->delete();

        session()
        ->flash('success','You Unliked the Article');

        return redirect()->back();
    }

   
    public function edit($id)
    {   
        $tag = Article::find($id)->tags->first();
       
        $article_data = Article::find($id);
            return view('article.edit',[
            'article_data' => $article_data,
            'tag' => $tag,
        ])->with('user',Auth::user());
    }
    public function update(ArticleRequest $request, $id)
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

        $article = Article::find($id);
        $article->title = request('title');
        $article->body = request('body');
        $article->save();
        $article->tags()->attach($tags_id);

        return redirect()
        ->action('ArticleController@show', $article->id)
        ->with('user',Auth::user());
    }

    public function destroy($id)
    {  
        $article = Article::find($id);
        $article->delete();

        return redirect(route('index'))
        ->with('success','記事を削除しました');
    }
}
