<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testshowSignUpPage()
    {
        $this->get('register')
        ->assertOk();
    }

    private function userData($overrides = [])
    {
        return array_merge([
            'name' => '太郎',
            'email' => 'aaa@bbb.com',
            'password' => 'abcd1234',
        ],$overrides);
    }
     /**ユーザー登録できるかテスト */
     public function testregister()
     {
        $this->withoutExceptionHandling();
         $userData = $this->userData();
 
         $this->post('register',$userData)
         ->assertStatus(302);

         unset($userData['password']);

         $this->assertDatabaseHas('users',$userData);

         $user = User::firstWhere($userData);

         $this->assertTrue(\Hash::check('abcd1234',$user->password));
     }
}
