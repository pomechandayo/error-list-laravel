<?php

namespace Tests\Feature\controllers;

use App\Article;
use App\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collectioin;


class ViewControllerTest extends TestCase
{
    use RefreshDatabase;

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
       $article = Article::factory()->create();

       $this->assertInstanceOf(Collection::class,$article->user);
   }
}
