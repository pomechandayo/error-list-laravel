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
    {
       $this->get('login')
       ->assertOk();
    }

    public function testlogin()
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
}
