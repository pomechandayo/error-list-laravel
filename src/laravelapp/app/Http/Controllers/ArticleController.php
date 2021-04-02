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
         
         $keyword = '新着記事一覧';
         $sort = $request->sort;
         ('request_tag');
         
         $keywords = $request->input('keyword');
         $keywords = implode(" ",$keywords);
         $tag_keyword = 0;
         /**
          * ここからタグ検索機能
          * 
          * 検索結果を$article_listに代入する
          * */
          
          // 検索ワードからタグの情報を抽出
          $tag_extract = preg_grep('/^tag:/',$request->input('keyword'));
          if( !empty($tag_extract)){
              $tag_keyword = str_replace('tag:',"",$tag_extract[0]);
            }
          
          
        /*検索フォームからタグのリクエストがあるか判定,無ければ新着記事を表示する*/
        
        if($tag_keyword == null)
        {  
            $article_list = Article::with('User')->orderBy('created_at','desc')->Paginate(10);
            
        }else{
            
                /*検索フォームの入力内容からtagのレコードを取り出す*/
                $tag_number = Tag::where('name',$tag_keyword)->first('id');
            
                
                /*検索結果がnullではない場合記事を取得、nullの場合検索結果0件を表示*/
                if($tag_number !== null){
                    //タグid($tag_number->id)から該当する記事のレコードを取り出す
                    $articles = Tag::find($tag_number->id)->articles->sortByDesc('created_at');
                    
                   
                    $keywords = str_replace('tag:',"",$keywords);
                    $keywords = str_replace($tag_keyword." ","",$keywords);
                    dd($articles);
                    $articles = $articles->filter(function($article) use ($keywords){
                    return strpos($article->title,$keywords) !==false;
                         });
                    $articles = $articles->filter(function($article)
                    use ($keywords){
                    return strpos($article->body,$keywords)
                     !==false; });
                       
                     if( $articles->isEmpty() == false ){
                         dd($articles);
                    foreach($articles as $article )
                    {
                        $get_article_list[] = $article->id;
                    }
                    /*記事情報と紐付けられたユーザー情報取得*/
                    $article_list = Article::with('User')->whereIn('id',$get_article_list)->orderBy('created_at','desc')->Paginate(10);
                    $keyword = '「'.$keywords.'」'.'の検索結果'; 
                    }
                    else{
                        $article_list = [];
                    $keyword = '「' . $keywords . '」' . 'に一致する記事はありませんでした'; 
                    }
                }else{
                    $article_list = [];
                    $keyword = '「' . $keywords . '」' . 'に一致する記事はありませんでした'; 

                }
            }

            /*ここまでタグ検索機能*/

          
            
            $keywords = str_replace('tag:',"",$keywords);
            $keywords = str_replace($tag_keyword." ","",$keywords);
           
            /**
             * 
             */
            if($request->keyword !== null || empty($tag_extract))
            {   
                
                $article_list = $article_list->filter(function($article) use ($keywords){
                    return strpos($article->title,$keywords) !==false;
                });
                $article_list = $article_list->filter(function($article)
                use ($keywords){
                    return strpos($article->body,$keywords)
                !==false; });
            
                
            }

            /**記事の合計数が0か判定する
             * 
             * 記事の合計を$articleTotalに代入する
            */
            if($article_list !== []){
              
                $query = $article_list;
                $articleTotal = count($query);
            }else{
                $articleTotal = 0;
            }
           
               return view('index',[
            'article_list' => $article_list,
            'sort' => $sort,
            'keyword' => $keyword,
            'articleTotal' => $articleTotal,
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
        return redirect()->action('ArticleController@show', $article->id)->with('user',Auth::user());
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

        return redirect(route('index'))->with('success','記事を削除しました');
    }
}
