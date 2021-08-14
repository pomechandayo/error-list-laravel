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
use App\UseCase\Article\NewArticleShowUseCase;
use App\UseCase\Article\TagKeywordSearch;
use App\UseCase\Article\TagAndFreeKeywordSearch;
use App\UseCase\Article\FreeKeywordSearch;
use App\UseCase\Article\ShowArticleUseCase;
use App\UseCase\Article\CreateUseCase;
use App\UseCase\Article\TagArticleSaveUseCase;
use App\UseCase\Article\EditUseCase;
use App\UseCase\Article\StatusUseCase;
use App\UseCase\Article\CommentUseCase;
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

    public function edit(int $article_id, EditUseCase $editUseCase)
    {   
        return $editUseCase->showEditPage($article_id);
    }

    public function update(ArticleRequest $request,TagArticleSaveUseCase $tagArticleSaveUseCase)
    {
        $article_id = $tagArticleSaveUseCase->articleUpdate($request);

        return redirect(route('article.show',[
            'articleId' => $article_id,
        ]));
    }

    /**記事の公開、非公開を切り替える */
    public function status(Request $request,StatusUseCase $statusUseCase)
    {
        $statusUseCase->switchStatus($request);
        
        return redirect()->back();
    }

    public function comment(CommentRequest $request,CommentUseCase $commentUseCase)
    {   
        $commentUseCase->createComment($request);

        return redirect()->back();
    }
    public function commentDelete(Request $request,CommentUseCase $commentUseCase)
    {
        $commentUseCase->deleteComment($request);

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

    public function destroy(Request $request)
    {  
        $article = Article::find($request->article_id);
        $article->delete();

        return redirect(route('index'))
        ->with('success','記事を削除しました');
    }
}
