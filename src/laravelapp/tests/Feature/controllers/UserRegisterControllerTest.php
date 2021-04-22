<?php

namespace Tests\Feature\Feature\controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class UserRegisterControllerTest extends TestCase
{
    use RefreshDatabase;
   
    public function testShowRegisterPage()
   {
       $this->get('register')
       ->assertSee('新規会員登録')
       ->assertSee('簡単ログイン')
       ->assertSee('メールアドレスで登録')
       ->assertOk();
   }

   public function testRedirect()
   {
       $url = 'register';

        $postData = [
        'name' => '',
        'email' => 'aaa@bbb.com',
        'password' => 'abcd1234',
    ];

    $this->from($url)
    ->post($url,$postData)
    ->assertRedirect($url);

    $this->get('register')
    ->assertSee('ユーザー名は必須です');
   }

   public function testRegistering()
   {
       $url = 'register';

       $postData = [
        'name' => '太郎',
        'email' => 'aaa@bbb.com',
        'password' => 'abcd1234',
       ];

       $this->from($url)->post($url,$postData)
       ->assertRedirect('login');       
   }
}
