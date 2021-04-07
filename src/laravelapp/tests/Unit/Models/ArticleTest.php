<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Collectioin;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Article;
use App\Tag;
use App\Comment;
use Tests\TestCase;

class ArticleTest extends TestCase
{

    /**article,tegのリレーション */
    public function testTagArticle()
    {
        $this->withoutExceptionHandling();
        $article_tag = Tag::find(1)->articles->first();
        
        $this->assertInstanceOf(Article::class,$article_tag);
    }

    /**article,commentリレーション */
    public function testArticleComment()
    {
        $article_comment = Comment::with('User')
        ->first();


        $this->assertInstanceOf(Article::class,
        $article_comment);
    }
}
