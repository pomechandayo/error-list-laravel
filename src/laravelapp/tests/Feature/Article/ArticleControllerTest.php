<?php

namespace Tests\Feature\Article;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ArticleRequest;
use App\User;
use App\Article;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    //バリデーション必須チェック用データ
    public function dataCreatePageValidatorRequired()
    {
        return [
            'title' => '',
            'tag' => '',
            'body' => ''
        ];
    }
    //バリデーション文字数上限チェック用データ
    public function dataCreatePageValidatorMaxString()
    {
        return [
            'title' => str_repeat('title',100),
            'tag' => '',
            'body' => str_repeat('body',1000)
        ];
    }

    //記事作成用データ
    public function dataCreateArticle()
    {
        return [
            'title' => str_repeat('title',10),
            'tags' => '#php',
            'body' => str_repeat('body',100)
        ];
    }

    //ログイン状態のユーザーデータを作る
    public function dataAuthenticate()
    {
        $userData = factory(User::class)->create();
        return $this->actingAs($userData);
    }

    // ここからindexメソッド
    /**
     * ページが表示されるか
     *
     * @return void
     */
    public function testShowPage()
    {
        $response = $this->get('/index');

        $response->assertStatus(200);
    }

    /**
     * ログイン状態でページが表示されるか
     *
     * @return void
     */
    public function testShowTopPageLogin()
    {
        $response = $this->dataAuthenticate();
        $response = $this->get('/index');

        $response->assertStatus(200);
    }

    /**
     * 記事が表示されるか
     * @return void
     */
    public function testNewArticle10()
    {
        $response = $this->post('/api/index',['']);

        $this->assertGreaterThan(3,$response['article_list']);
    }

    public function keywordData()
    {
        return ['tag:php'];
    }
    /**
     * 検索結果が表示されるか
     *
     * @return void
     */
    public function testSearchArticle()
    {
        $keyword = $this->keywordData();
        $response = $this->post('/api/index',$keyword);

        $response->assertOk();
    }

    /**
     * 検索したとき検索キーワードが検索欄の上に表示されるか
     *
     * @return void
     */
    public function testShowSearchWord()
    {
        $response = $this->post('/api/index',['']);;

        $this->assertEquals($response['search_keyword'],'新着記事一覧');
    }


    /**
     * ページネーション機能が使えるか
     *
     * @return void
     */
    public function testTopPagenation()
    {
        $response = $this->post('/api/index',['']);

        $this->assertEquals(1,$response['article_list']['current_page']); 
        $this->assertEquals(5,$response['article_list']['last_page']);
        $this->assertEquals("http://local/api/index?page=2",$response['article_list']['next_page_url']);
    }
    // ここまでindexメソッド

    //ここからcreateメソッド
    /**
     * 記事作成ページが表示されるか
     */
    public function testShowCreatePage()
    {
        $response = $this->dataAuthenticate();

        $response->get('/article/create')->assertOk();
    }

    /**
      *バリデーションが機能しているかのテスト
      *必須エラー
      */
    public function testCreatePageValidatorRequired()
    {
        $dataList = $this->dataCreatePageValidatorRequired();
        $request = new ArticleRequest();

        $rules = $request->rules();
        $validator = Validator::make($dataList,$rules);

        $validation_result = $validator->passes();

        $this->assertFalse($validation_result);
    }

    /**
      *バリデーションが機能しているかのテスト
      *文字数上限エラー
      */
    public function testCreatePageValidatorMaxString()
    {
        $dataList = $this->dataCreatePageValidatorMaxString();
        $request = new ArticleRequest();

        $rules = $request->rules();

        $validator = Validator::make($dataList,$rules);

        $validation_result = $validator->passes();

        $this->assertFalse($validation_result);
    }

    //ここまでcreateメソッド
    //ここからstoreメソッド
    
    /**
     * 記事が作成できるか
     */
    public function testCreateArticle()
    {   
        $articleData = $this->dataCreateArticle();
        $response = $this->dataAuthenticate();

        $response = $response->post('/article',$articleData);

        $response->assertRedirect();

        $createArticleData = Article::with('tags')->where('title',$articleData['title'])
        ->first()
        ->toArray();
        
        //$articleDataのtag情報から#を抜き出す
        $tag = str_replace('#','',$articleData['tags']);

        $this->assertEquals($articleData['title'],$createArticleData['title']);
        $this->assertEquals($tag,$createArticleData['tags'][0]['name']);
        $this->assertEquals($articleData['body'],$createArticleData['body']);
    }

    /**
     * タグをつけて記事を作成できるか
     */
    public function testCreateArticleTag()
    {
        $this->get('/index')->assertOk();
    }

    /**
     * マークダウンで記述されているか
     */
    public function testCreateArticleMarkdown()
    {
        $this->get('/index')->assertOk();
    }
    //ここまでstoreメソッド

    //ここからshowメソッド
     /**
     * 記事詳細ページが表示されるか
     *
     * @return void
     */
    public function testTopPagetoShowPage()
    {
        $response = $this->get('/api/article/show/2');

        $response->assertStatus(200);
    }
    //ここまでshowメソッド

    //ここからeditメソッド
    /**
     * ページが表示されるか
     */
    public function testShowEditPage()
    {

    }
    /**
     * バリデーションが機能しているか
     */
    public function testShowEditPageVaridation()
    {
        
    }
    /**
     * バリデーションの文言が表示されるか
     */
    public function testShowEditPageVaridationWord()
    {
        
    }
    //ここまでeditメソッド

    //ここからupdateメソッド
    /**
     * 記事が編集できるか
     */
    public function testArticleUpdate()
    {

    }
    /**
     * タグをつけて記事が編集できるか
     */
    public function testArticleUpdateTag()
    {
        
    }    
    /**
     * マークダウンで記事が編集されているか
     */
    public function testArticleUpdateUpdate()
    {
        
    }
    //ここまでupdateメソッド

    //ここからdestoryメソッド
    /**
     * 記事が削除できるか
     */
    public function testDeleteArticle()
    {

    }
    /**
     * トップページにリダイレクトされるか
     */
    public function testDeleteRedirectTop()
    {

    }
    //ここまでdestoryメソッド

    //ここからstatusメソッド
    /**
     * 記事を公開状態に変更できるか
     */
    public function testArticleOpen()
    {

    }
    /**
     * 記事を非公開状態に変更できるか
     */
    public function testArticleClose()
    {
        
    }
    //ここまでstatusメソッド

    //ここからcommentメソッド
    /**
     * コメントできるか
     */
    public function testArticleComment()
    {
        
    }    
    /**
     * 記事詳細ページにリダイレクトされるか
     */
    public function testRedirectArticleShowPageComment()
    {
        
    }
    /**
     * バリデーションが機能するか
     */
    public function testCommentValidation()
    {
        
    }
    /**
     * バリデーションの文言が表示されるか
     */
    public function testCommentValidationWord()
    {
        
    }
    //ここまでcommentメソッド

    //ここからcommentdeleteメソッド
    /**
     * コメント削除できるか
     */
    public function testArticleCommentDelete()
    {
        
    }
     /**
     * 記事詳細ページにリダイレクトされるか
     */
    public function testRedirectArticleShowPageCommentdelete()
    {
        
    }    
    //ここまでcommentdeleteメソッド

    //ここからreplyメソッド
    /**
     * リプライできるか
     */
    public function testArticleReply()
    {
        
    }
    /**
     * 記事詳細ページにリダイレクトされるか
     */
    public function testRedirectArticleShowPageReply()
    {
        
    }
    /**
     * バリデーションが機能するか
     */
    public function testReplyValidation()
    {
        
    }
    /**
     * バリデーションの文言が表示されるか
     */
    public function testReplyValidationWord()
    {
        
    }
    //ここまでreplyメソッド

    //ここからreplydeleteメソッド
    /**
     * リプライが削除できるか
     */
    public function testReplyDelete()
    {
        
    }
     /**
     * 記事詳細ページにリダイレクトされるか
     */
    public function testRedirectArticleShowPageReplyDelete()
    {
        
    }
    //ここまでreplydeleteメソッド
}
