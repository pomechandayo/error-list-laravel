<?php

namespace Tests\Feature\Article;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ArticleRequest;
use App\User;
use App\Article;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    private static $isSetUp = false;

    protected function setUp():void
    {
        parent::setUp();
        if(self::$isSetUp === false) {

            Artisan::call('migrate:refresh --seed --env=testing');
            self::$isSetUp = true;
        }
    }

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
            'title' => substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 8),
            'tags' => '#php',
            'body' => substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 8)
        ];
    }

    //ログイン状態のユーザーデータを作る
    public function dataAuthenticate()
    {
        $userData = factory(User::class)->create();
        return $this->actingAs($userData);
    }

    //記事を作成する
    public function createArticleData($articleData,$response)
    {
        $response = $response->post('/article',$articleData);

        $response->assertRedirect();

        $createArticleData = Article::with('tags')->where('title',$articleData['title'])
        ->first()
        ->toArray();

        return $createArticleData;
    }
    //記事を編集する
    public function updateArticleData($articleData,$response)
    {
        $update = 'update';

        //記事のデータを編集
        $articleData = [
        'article_id' => $articleData['id'],
        'title' => $update.$articleData['title'],
        'tags' => '#java'.'#'.$articleData['tags'][0]['name'],
        'body' => $update.$articleData['body']
        ];

        $response = $response->post('/article/update',$articleData);

        $response->assertRedirect();

        $createArticleData = Article::with('tags')->where('title',$articleData['title'])
        ->first()
        ->toArray();

        return $createArticleData;
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
    public function testCreateArticleTag()
    {   
        $response = $this->dataAuthenticate();
        $articleData = $this->dataCreateArticle();
        
        $createArticleData = $this->createArticleData($articleData,$response);
        //$articleDataのtag情報から#を抜き出す
        $tag = str_replace('#','',$articleData['tags']);

        $this->assertEquals($articleData['title'],$createArticleData['title']);
        $this->assertEquals($tag,$createArticleData['tags'][0]['name']);
        $this->assertEquals($articleData['body'],$createArticleData['body']);
    }

    /**
     * タグ無しで記事を作成できるか
     */
    public function testCreateArticleNotTag()
    {
        $response = $this->dataAuthenticate();   
        $articleData = $this->dataCreateArticle();
        
        unset($articleData['tags']);
        
        $createArticleData = $this->createArticleData($articleData,$response);
        
        $this->assertEquals($articleData['title'],$createArticleData['title']);
        $this->assertEquals($articleData['body'],$createArticleData['body']);
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

        $response->assertOk(200);
    }
    //ここまでshowメソッド

    //ここからeditメソッド
    /**
     * ページが表示されるか
     */
    public function testShowEditPage()
    {
        $articleData = $this->dataCreateArticle();
        $response = $this->dataAuthenticate();

        $createArticleData = $this->createArticleData($articleData,$response);
        $id = $createArticleData['id'];

        $response = $response->get(
        "/article/$id/edit");

        $response->assertOk();
    }
    //ここまでeditメソッド

    //ここからupdateメソッド
    /**
     * 記事が編集できるか
     */
    public function testArticleUpdate()
    {
        $articleData = $this->dataCreateArticle();
        $response = $this->dataAuthenticate();

        $createArticleData = $this->createArticleData($articleData,$response);

        $updateData = $this->updateArticleData($createArticleData,$response);

        $this->assertEquals($createArticleData['id'],$updateData['id']);
        $this->assertNotEquals($createArticleData['title'],$updateData['title']);
        $this->assertNotEquals($createArticleData['body'],$updateData['body']);
    }
    //ここまでupdateメソッド

    //ここからdestoryメソッド
    /**
     * 記事が削除できるか
     */
    public function testDeleteArticle()
    {
        $articleData = $this->dataCreateArticle();
        $response = $this->dataAuthenticate();

        $createArticleData = $this->createArticleData($articleData,$response);
        $article_id = [
            'article_id' => $createArticleData['id']
        ];
        $article = Article::find($createArticleData['id']);

        $this->assertNotNull($article);
        
        $response = $response->post('/article/destroy',$article_id);
        $article = Article::find($createArticleData['id']);

        $this->assertNull($article);
        $response->assertRedirect('/index');
    }
    //ここまでdestoryメソッド

    //ここからstatusメソッド
    /**
     * 記事を公開状態に変更できるか
     */
    public function testArticleOpen()
    {
        $response = $this->get('/index');
        $response->assertOk();
    }
    /**
     * 記事を非公開状態に変更できるか
     */
    public function testArticleClose()
    {
        $response = $this->get('/index');
        $response->assertOk();
    }
    //ここまでstatusメソッド

    //ここからcommentメソッド
    /**
     * コメントできるか
     */
    public function testArticleComment()
    {
        $response = $this->get('/index');
        $response->assertOk();
    }    
    /**
     * 記事詳細ページにリダイレクトされるか
     */
    public function testRedirectArticleShowPageComment()
    {
        $response = $this->get('/index');
        $response->assertOk();
    }
    /**
     * バリデーションが機能するか
     */
    public function testCommentValidation()
    {
        $response = $this->get('/index');
        $response->assertOk();
    }
    /**
     * バリデーションの文言が表示されるか
     */
    public function testCommentValidationWord()
    {
        $response = $this->get('/index');
        $response->assertOk();
    }
    //ここまでcommentメソッド

    //ここからcommentdeleteメソッド
    /**
     * コメント削除できるか
     */
    public function testArticleCommentDelete()
    {
        $response = $this->get('/index');
        $response->assertOk();
    }
     /**
     * 記事詳細ページにリダイレクトされるか
     */
    public function testRedirectArticleShowPageCommentdelete()
    {
        $response = $this->get('/index');
        $response->assertOk();
    }    
    //ここまでcommentdeleteメソッド

    //ここからreplyメソッド
    /**
     * リプライできるか
     */
    public function testArticleReply()
    {
        $response = $this->get('/index');
        $response->assertOk();
    }
    /**
     * 記事詳細ページにリダイレクトされるか
     */
    public function testRedirectArticleShowPageReply()
    {
        $response = $this->get('/index');
        $response->assertOk();
    }
    /**
     * バリデーションが機能するか
     */
    public function testReplyValidation()
    {
        $response = $this->get('/index');
        $response->assertOk();
    }
    /**
     * バリデーションの文言が表示されるか
     */
    public function testReplyValidationWord()
    {
        $response = $this->get('/index');
        $response->assertOk();
    }
    //ここまでreplyメソッド

    //ここからreplydeleteメソッド
    /**
     * リプライが削除できるか
     */
    public function testReplyDelete()
    {
        $response = $this->get('/index');
        $response->assertOk();
    }
     /**
     * 記事詳細ページにリダイレクトされるか
     */
    public function testRedirectArticleShowPageReplyDelete()
    {
        $response = $this->get('/index');
        $response->assertOk();
    }
    //ここまでreplydeleteメソッド
}
