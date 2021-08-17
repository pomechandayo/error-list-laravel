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
use App\UseCase\Article\ArticleCrudUseCase;
use App\UseCase\Article\CreateUseCase;
use App\UseCase\Article\TagArticleSaveUseCase;
use App\UseCase\Article\EditUseCase;
use App\UseCase\Article\StatusUseCase;
use App\UseCase\Article\CommentUseCase;
use App\UseCase\Article\ReplyUseCase;
use Illuminate\Support\Facades\Storage;
use App\GetClass;

class ArticleController extends Controller
{
    public function index(Request $request)
    { 
        //検索ワードを変数に格納
        $tag_keyword = implode($request->input('tag_keyword'));
        $free_keyword = implode($request->input('free_keyword'));
        $article_list = [];
              
            $interface = new Getclass($tag_keyword,$free_keyword);

            $article_list = $interface->searchClass();

        // コレクションを配列に変換
        if( !is_array($article_list)) {
            $article_list = $article_list->toArray();
        }

        if( empty($article_list)){
            $search_keyword = $tag_keyword." ". $free_keyword .'に一致する検索結果はありませんでした';
        }else {
            $search_keyword = $tag_keyword." ". $free_keyword .'の検索結果';
        }

        if( empty($tag_keyword) && empty($free_keyword)){

            $search_keyword = '新着記事';
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

    public function store(ArticleRequest $request,ArticleCrudUseCase $articleCrudUseCase)
    {  
        
        $articleCrudUseCase->articleCreate($request);

        return redirect()->route('index');
    }
   
    public function show(ArticleCrudUseCase $articleCrudUseCase,int $id)
    {   
       return $articleCrudUseCase->showArticle($id,Auth::id());
    }

    public function edit(int $article_id, EditUseCase $editUseCase)
    {   
        return $editUseCase->showEditPage($article_id);
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
