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
use App\UseCase\Article\ArticleCrudUseCase;
use App\UseCase\Article\SearchWordUseCase;
use App\UseCase\Article\CreateUseCase;
use App\UseCase\Article\EditUseCase;
use App\UseCase\Article\StatusUseCase;
use App\UseCase\Article\CommentUseCase;
use App\UseCase\Article\ReplyUseCase;
use Illuminate\Support\Facades\Storage;
use App\GetClass\Search;
use App\GetClass\ShowArticle;

class ArticleController extends Controller
{
    public function index(Request $request,SearchWordUseCase $searchWordUseCase)
    { 
        //検索ワードを変数に格納
        $tag_keyword = implode($request->input('tag_keyword'));
        $free_keyword = implode($request->input('free_keyword'));
        $article_list = [];
              
        $interface = new Search($tag_keyword,$free_keyword);

        $article_list = $interface->searchClass();

        // コレクションを配列に変換
        if( !is_array($article_list)) {
            $article_list = $article_list->toArray();
        }

        $search_keyword = $searchWordUseCase->SearchKeywordSelect($tag_keyword,$free_keyword,$article_list);

        return [
            'article_list' => $article_list,
            'search_keyword' => $search_keyword, 
        ];

    }
                
    public function create()
    {   
        $id = 0;

        $interface = new ShowArticle($id,'create');

        return $interface->showArticleClass();
    }

    public function store(ArticleRequest $request,ArticleCrudUseCase $articleCrudUseCase)
    {  
        
        $articleCrudUseCase->articleCreate($request);

        return redirect()->route('index');
    }
   
    public function show(int $id)
    {   
        $interface = new ShowArticle($id,'show');
        return $interface->showArticleClass();
    }

    public function edit(int $article_id)
    {   
        $interface = new ShowArticle($article_id,'edit');
        return $interface->showArticleClass();
    }

    public function update(ArticleRequest $request,ArticleCrudUseCase $articleCrudUseCase)
    {
        $article_id = $articleCrudUseCase->articleUpdate($request);

        return redirect(route('article.show',[
            'articleId' => $article_id,
        ]));
    }

    public function destroy(Request $request,ArticleCrudUseCase $articleCrudUseCase)
    {  
        return $articleCrudUseCase->deleteArticle($request);
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

    public function reply(CommentRequest $request,ReplyUseCase $replyUseCase)
    { 
        $replyUseCase->createReply($request,Auth::id());
        
        return redirect()->back();
    }
    public function replyDelete(Request $request,ReplyUseCase $replyUseCase)
    {
        $replyUseCase->deleteReply($request);
        
        return redirect()->back();
    }
}
