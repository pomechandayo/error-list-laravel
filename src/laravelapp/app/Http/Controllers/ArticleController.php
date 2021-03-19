<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Article;
use App\Tag;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Article::all();
        return $items->toArray();
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
        $validate = $request->validate([
            'title' => 'required|max:50',
            'body' => 'required|max:2000',
        ]);
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
        $post->tags()->attach($tags_id);
        $post->title = $request->title;
        $post->body = $request->body;
        $post->user_id = Auth::user()->id;
        $post->save();

        return redirect()->route('index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = ArticlePost::find($id);
        return $item->toArray();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
