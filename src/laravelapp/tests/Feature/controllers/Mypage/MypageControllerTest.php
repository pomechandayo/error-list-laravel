<?php

namespace Tests\Feature\Controllers\Mypage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Article;
use App\User;
use Tests\TestCase;


class MypageControllerTest extends TestCase
{
    use RefreshDatabase;
  
    /**アクセスした時認証されていない場合はログインページにリダイレクト */
    public function testNotauthenticated()
    {
        $url = 'login';

        $this->get('mypage/profile')
        ->assertRedirect();
    }
    private function login(User $user = null)
    {
        $user = factory(User::class,1)->create();

        $this->actingAs($user);

        return $user;
    }
    /**認証されている場合ページを表示 */
    public function Authenticated()
    {
        $user = $this->login();

        $other = factory(Article::class,1)->create();
        $myArticle = factory(Article::class,1)
        ->create(['user_id' => $user]);
        
        $this->get('mypage/profile')
        ->assertOk()
        ->assertDontSee($other->title)
        ->assertSee($myArticle->title);

    }
}
