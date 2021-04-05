<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Article;
use App\Tag;
use App\User;
use App\Article_tag;
use App\Http\Requests\ArticleRequest;
use App\ArticleTag;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            ->orderBy('created_at','desc')->Paginate(10);
        
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
                $article_list = Tag::find($tag_number->id)->articles->sortByDesc('created_at');
           
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
                   ->whereIn  ('id',$get_article_list)
                   ->orderBy('created_at','desc')->Paginate(10);
                   
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
           ->whereIn  ('id',$get_article_list)               ->orderBy('created_at','desc')
           ->Paginate(10);
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
                    ->whereIn  ('id',$get_article_list)
                    ->orderBy('created_at','desc')->Paginate(10);
                   
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
                
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('article.create')
        ->with('user',Auth::user());;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *       投稿した内容のレコードを作成
     */
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
        $post->save();
        $post->tags()->attach($tags_id);
        
        return redirect()->route('index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {   

        $article = Article::with('User')->find($id);
        $article_parse = new Article;
        $article_parse_body = $article->parse($article_parse);
       
        return view('article.show',[
            'article' => $article,
            'article_parse_body' => $article_parse_body
        ])->with('user',Auth::user());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        
        $tag = Article::find($id)->tags->first();
       
        $article_data = Article::find($id);
            return view('article.edit',[
            'article_data' => $article_data,
            'tag' => $tag,
        ])->with('user',Auth::user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, $id)
    {   
        
    
        $article = Article::find($id);
        $article->title = request('title');
        $article->body = request('body');
        $article->save();

        return redirect()
        ->action('ArticleController@show', $article->id)
        ->with('user',Auth::user());
    }

  
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {  
        $article = Article::find($id);
        $article->delete();

        return redirect(route('index'))
        ->with('success','記事を削除しました');
    }
}
