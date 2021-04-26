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
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index(User $user,Request $request)
    {         
        $keywords_array = $request->input('keyword');
        $keywords = implode(" ",$keywords_array);
        $article_list = [];
        $user_image = User::GetS3Url();
        $s3_profile_image = User::GetAuthUserImage();

        /*検索フォームからタグのリクエストがあるか判定,
        無ければ新着記事を表示する*/
        if($keywords === "") {  
           $article_list = Article::with('user','tags','likes','comments')
           ->ArticleOpen()
           ->Created_atDescPaginate();
           $keyword = '新着記事一覧';

           return view('index',[
                'article_list' => $article_list,
                'keyword' => $keyword,
                'keywords' => $keywords,
                'user_image' => $user_image,
                's3_profile_image' => $s3_profile_image,
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
                
                    return view('index',[
                        'article_list' => $article_list,
                        'keyword' => $search_keyword,
                        's3_profile_image' => $s3_profile_image,
                     ])->with('user',Auth::user());
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
        
                    return view('index',[
                        'article_list' => $article_list,
                        'keyword' => $search_keyword,
                        's3_profile_image' => $s3_profile_image,
                     ])->with('user',Auth::user());
                }
        } 
                 
            $search_keyword = $keywords . 'の検索結果';

             return view('index',[
                 'search_keyword' => $search_keyword, 
                 'article_list' => $article_list,
                 'keywords' => $keywords,
                 'user_image' => $user_image,
                 's3_profile_image' => $s3_profile_image,
             ])->with('user',Auth::user());
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
        $user_image = User::GetS3Url();
        $s3_profile_image = User::GetAuthUserImage();

        $comments = Comment::with(['user','replies','replies.user'])->where('article_id',$id)->get();       
        
        $article = Article::with('User','tags')
        ->find($id);
        $article_parse = new Article;
        $article_parse_body = $article->parse($article_parse);

    
        if($article->status === Article::CLOSED &&
        Auth::id() !== $article->user->id) {
            return redirect()->route('index');
        }else{
            return view('article.show',[
                'user_image' => $user_image,
                's3_profile_image' => $s3_profile_image,
                 'article' => $article,
                 'comments' => $comments,
                 'user_id' => Auth::id(),
                 'article_parse_body' => $article_parse_body,
             ])->with('user',Auth::user());
        }
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
        
        return redirect()->action('ArticleController@show',
         $request->article_id); 
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

    public function like(Request $request)
    {
        $user_id = Auth::user()->id; 
        $article_id = $request->article_id;
        
        $already_liked = Like::where('user_id', $user_id)->where('article_id', $article_id)->first();   
        if (!$already_liked) {   
            Like::create([
                'article_id' => $article_id,
                'user_id' => $user_id 
            ]);
        } else { 
            Like::where('article_id', $article_id)->where('user_id', $user_id)->delete();
        }
        //5.この投稿の最新の総いいね数を取得
        $review_likes_count = Article::withCount('likes')->findOrFail($article_id)->likes_count;
        $param = [
            'review_likes_count' => $review_likes_count,
        ];
        return response()->json($param); //6.JSONデータをjQueryに返す
    }

   
    public function edit(int $id)
    {   
        $s3_profile_image = User::GetAuthUserImage();
        $tag_list = Article::find($id)->tags->pluck('name');
        $tags = $tag_list->toArray();
        $tags_string = implode(" #",$tags);

        $article_data = Article::find($id);
      
        $article_parse = new Article;
        $article_parse_body = $article_data->parse($article_parse);

        return view('article.edit',[
            's3_profile_image' => $s3_profile_image,
            'article_parse_body' => $article_parse_body,
            'article_data' => $article_data,
            'tag' => $tags_string,
        ])->with('user',Auth::user());
    }
    public function update(ArticleRequest $request, int $id)
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
    DB::transaction(function () use ($article,$tags_id)
    {
        $article->save();
        $article->tags()->attach($tags_id);
    });

        return redirect()
        ->action('ArticleController@show', $article->id)
        ->with('user',Auth::user());
    }

    public function destroy(int $id)
    {  
        $article = Article::find($id);
        $article->delete();

        return redirect(route('index'))
        ->with('success','記事を削除しました');
    }
}
