<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Collectioin;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Article;
use App\Comment;
use App\User;
use App\Tag;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    /**articles */
    public function testUserArticle()
    {
        $this->withoutExceptionHandling();
        
        $eloquent = app(Article::class);
      
        $this->assertEmpty($eloquent->get()); //初期状態は空か確認
        
    }

}
