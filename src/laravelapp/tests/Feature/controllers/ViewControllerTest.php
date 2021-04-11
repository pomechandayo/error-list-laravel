<?php

namespace Tests\Feature\controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Collectioin;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Comment;
use App\Article;
use App\User;
use App\Tag;
use Tests\TestCase;



class ViewControllerTest extends TestCase
{
    use RefreshDatabase;

   /**test index タイトル、ユーザー名表示 */
   public function testShowIndex()
   {    
        $article1 = factory(Article::class,1)
        ->create();
        $article2 = factory(Article::class,1)
        ->create();
        $article3 = factory(Article::class,1)
        ->create();
        

        $users = User::get()->toArray();

        $this->get('/index')
        ->assertOK()
        ->assertSee($article1->pluck('title')->first())
        ->assertSee($article2->pluck('title')->first())
        ->assertSee($article3->pluck('title')->first())
        ->assertSee($users[0]['name'])
        ->assertSee($users[1]['name'])
        ->assertSee($users[2]['name']);
   }
   /**非公開設定した記事が非公開になっているかテスト */
   public function testArticleClosed()
   { 
        $article = factory(Article::class,1)
        ->create(['status' => Article::CLOSED]);

        $this->get('/index')
        ->assertDontSee($article->pluck('title')->first());
   }
   /**公開設定した記事が公開になっているかテスト */
   public function testArticleOpen()
   { 
        $article1 = factory(Article::class,1)
        ->create(['status' => Article::OPEN]);

        $this->get('/index')
        ->assertSee($article1->pluck('title')->first());
   }
  
}
