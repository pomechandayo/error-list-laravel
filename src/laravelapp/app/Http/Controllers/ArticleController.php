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
         /*記事情報と紐付けられたユーザー情報取得*/

         $sort = $request->sort;
         $article_list = Article::with('User')->orderBy('created_at','desc')->Paginate(10);
    
         dd(Tag::find(1)->articles());
         
        return view('index',[
        'article_list' => $article_list,
        'sort' => $sort,
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
    public function store(Request $request)
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
       
        $article_data = Article::find($id);
        return view('article.edit',[
            'article_data' => $article_data,
        ])->with('user',Auth::user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
