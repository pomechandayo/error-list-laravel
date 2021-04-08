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
    

   /**test index */
   public function test_index()
   {    
        $this->withoutExceptionHandling();
    
        $this->get('/index')
        ->assertOk();

   }

   /** 記事のユーザーとリレーションチェック*/
   public function testArticle()
   {
        $articles = Article::with('User')->first();
      
        $this->get('/index')
        ->assertSee($articles->title)
        ->assertSee($articles->user->name);
   }

   /**非公開状態の記事がindexページで表示されていないかチェック */
   public function testArticleClosed()
   {
       $articles1 = Article::where('status',Article::CLOSED)
       ->first();

       $this->get('/index')
       ->assertDontSee($articles1->title);
   }
   /**公開状態の記事がindexで表示されているかチェック */
   public function testArticleOpen()
   {
       $articles1 = Article::where('status',Article::OPEN)
       ->first();

       $this->get('/index')
       ->assertSee($articles1->title);
   }

}
