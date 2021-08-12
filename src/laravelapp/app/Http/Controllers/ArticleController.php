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
use App\Article\NewArticleShowUseCase;
use App\Article\TagKeywordSearch;
use App\Article\TagAndFreeKeywordSearch;
use App\Article\FreeKeywordSearch;
use App\Article\ShowArticleUseCase;
use App\Article\CreateUseCase;
use App\Article\TagArticleSaveUseCase;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index(
        Request $request, 
        NewArticleShowUseCase $newArticleShowUseCase,
        TagAndFreeKeywordSearch $tagAndFreeKeywordSearch,
        TagKeywordSearch $tagKeywordSearch,
        FreeKeywordSearch $freeKeywordSearch
        )
    { 
        //検索ワードを変数に格納
        $tag_keyword = implode($request->input('tag_keyword'));
        $free_keyword = implode($request->input('free_keyword'));
        $article_list = [];
   
        /** 
         * 新着記事を表示する
        */
        if(empty($tag_keyword) && empty($free_keyword)) {  
              
            $article_list = $newArticleShowUseCase->newArticle10();

            $search_keyword = '新着記事一覧';
        }
        
        /**
         * タグ検索する
         */
        if(!empty($tag_keyword)) {  
         
            $article_list = $tagKeywordSearch->getArticleList($tag_keyword);

            $search_keyword = $tag_keyword .'の検索結果';
        }

        /**
         * タグとフリ-ワードで検索 
         */ 

        if( !empty($tag_keyword ) && !empty($free_keyword)) {   
            
            $article_list = $tagAndFreeKeywordSearch->getArticleList($free_keyword,$article_list);

            $search_keyword = $tag_keyword ." ". $free_keyword . 'の検索結果';
        }

        /**
         * フリーキーワードのみの検索の場合
         */
    if( empty($tag_keyword) && !empty($free_keyword)) {   
            $article_list = $freeKeywordSearch->getArticleList($free_keyword);
            
            $search_keyword = $free_keyword.'の検索結果';
        }

        // コレクションを配列に変換
        if( !is_array($article_list)) {
            $article_list = $article_list->toArray();
        }

        if( empty($article_list)){
            $search_keyword = $tag_keyword." ". $free_keyword .'に一致する検索結果はありませんでした';
        }

        return [
            'article_list' => $article_list,
            'search_keyword' => $search_keyword, 
        ];
    }
                
    public function create(CreateUseCase $createUseCase)
    {   
        return $createUseCase->showCreatePage();
    }

    public function store(ArticleRequest $request,TagArticleSaveUseCase $tagArticleSaveUseCase)
    {  
        
        $tagArticleSaveUseCase->articleSave($request);

        return redirect()->route('index');
    }
   
    public function show(ShowArticleUseCase $showArticleUseCase,int $id)
    {   
       return $showArticleUseCase->showArticle($id,Auth::id());
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
