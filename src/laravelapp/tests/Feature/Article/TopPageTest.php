<?php

namespace Tests\Feature\Article;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

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

        assertEquals($response['search_keyword'],'新着記事一覧');
    }

    /**
     * 記事詳細ページに推移できるか
     *
     * @return void
     */
    public function testTopPagetoShowPage()
    {
        $response = $this->get('/show'/1);
dd($response);
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
