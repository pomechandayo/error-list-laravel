<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\GetClass\Search;


class SearchTest extends TestCase
{
  private static $isSetUp = false;

  protected function setUp():void
  {
      parent::setUp();

      if(self::$isSetUp === false) {

          $this->artisan('migrate:fresh --seed');
          self::$isSetUp = true;
      }
  }

    /**
     * 
     * キーワードない時新着記事表示
    */
    public function testLatestArticle()
    {
        $response = $this->post('/api/index',['']);;
      
        $this->assertGreaterThan(3,$response['article_list']);
        
    }

    /**
     * 
     * フリーワード検索
    */
    public function testFreewordSearch()
    {
        $response = $this->post('/api/index',['keyword' => 'f']);;

        $this->assertEquals(' fの検索結果',$response['search_keyword']);

    }

    /**
     * 
     * フリーワード検索該当する記事ない場合
    */
    public function testFreewordSearchNotArticle()
    {
        $response = $this->post('/api/index',['keyword' => 'ファ']);;

        $this->assertEquals(' ファに一致する検索結果はありませんでした',$response['search_keyword']);

    }
}