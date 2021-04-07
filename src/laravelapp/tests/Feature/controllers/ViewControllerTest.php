<?php

namespace Tests\Feature\controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Collectioin;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Article;
use App\Tag;
use App\Comment;
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

   /**test article */
   public function testArticle()
   {
        $this->withoutExceptionHandling();
        
        $articles = Article::with('User')->first();
      
        $this->get('/index')
        ->assertSee($articles->title)
        ->assertSee($articles->body)
        ->assertSee($articles->user->name);

   }

   public function testShowComment()
   {
    $comment = Comment::find(1);

    $this->get('/article/show')
    ->assertSee($comment->body);
   }

   public function testArticleClosed()
   {
       $this->withoutExceptionHandling();

       $articles1 = Article::where('status',Article::CLOSED)
       ->first();

       $this->get('/index')
       ->assertDontSee($articles1->title);

   }
   public function testArticleOpen()
   {
       $this->withoutExceptionHandling();

       $articles1 = Article::where('status',Article::OPEN)
       ->first();

       $this->get('/index')
       ->assertSee($articles1->title);

   }
}
