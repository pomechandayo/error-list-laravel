<?php

namespace Tests\Feature\Article;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Article\NewArticleShowUseCase;
use App\Article\SearchArticleUseCase;
use App\User;
use Tests\TestCase;

class TopPageTest extends TestCase
{
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
     * 記事が表示されるか
     * 
     * 
     * @return void
     */
    public function testNewArticle10()
    {
        $newArticleShowUseCase = new NewArticleShowUseCase;
        $article_list = $newArticleShowUseCase->newArticle10();

        $this->assertCount(10,$article_list['article_list']);
    }

    /**
     * 検索結果が表示されるか
     *
     * @return void
     */
    public function testSearchArticle()
    {
        $searchArticleUseCase = new testSearchArticleUseCase;
        
    }

    /**
     * 検索キーワードが検索欄の上に表示されるか
     *
     * @return void
     */
    public function testShowSearchWord()
    {
        $response = $this->get('/index');

        $response->assertStatus(200);
    }

    /**
     * 記事詳細ページに推移できるか
     *
     * @return void
     */
    public function testTopPagetoShowPage()
    {
        $response = $this->get('/index');

        $response->assertStatus(200);
    }

    /**
     * 記事詳細ページに推移できるか
     *
     * @return void
     */
    public function testTopPagetoUserPage()
    {
        $response = $this->get('/index');

        $response->assertStatus(200);
    }

    /**
     * ページネーション機能が使えるか
     *
     * @return void
     */
    public function testTopPagenation()
    {
        $response = $this->get('/index');

        $response->assertStatus(200);
    }

    /**
     * ログイン時に記事が表示されるか
     *
     * @return void
     */
    public function testLoginShowTopPage()
    {
        $response = $this->get('/index');

        $response->assertStatus(200);
    }

    /**
     * ログイン時に自分のアイコンタップでマイページに推移するか
     *
     * @return void
     */
    public function testTopPagetoMypage()
    {
        $response = $this->get('/index');

        $response->assertStatus(200);
    }
}
