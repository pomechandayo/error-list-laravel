<?php

namespace Tests\Feature\Controllers\Mypage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class UserLoginControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testShowLogin()
    { $this->withoutExceptionHandling();
       $this->get('login')
       ->assertOk();

       $this->get('login')
       ->assertSee('ログイン')
       ->assertSee('簡単ログイン')
       ->assertSee('メールアドレスでログイン');
    }

    public function testLogin()
    {
        $postData = [
            'email' => 'aaa@bbb.com',
            'password' => 'abcd1234'
        ];
        $dbData = [
            'email' => 'aaa@bbb.com',
            'password' => bcrypt('abcd1234')
        ];

        $userData = factory(User::class,1)->create($dbData);
       
        $this->post('login',$postData)
        ->assertRedirect('/index');

    }

    public function testFailedLogin()
    {
        $postData = [
            'email' => 'aaa@bbb.com',
            'password' => 'abcd1234'
        ];
        $dbData = [
            'email' => 'ccc@bbb.com',
            'password' => bcrypt('abcd1234')
        ];

        $url = 'login';

        $this->From($url)->post($url,$postData)
        ->assertRedirect($url);

        $this->get($url)
        ->assertSee('メールアドレスかパスワードが間違っています');
    }
}
