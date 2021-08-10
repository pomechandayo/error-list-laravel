<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserRequest;
use Tests\TestCase;
use App\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**ユーザー登録ページ表示テスト */
    public function testshowSignUpPage()
    {
        $url = $this->get('/register');

        $url->assertOk();
    }

    /**
    * 簡単ログインでログインできるかチェック 
    */
    public function testEasyLogin()
    {
        $userData = factory(User::class)
        ->state('login')
        ->make()
        ->toArray();

         $this->post('/register',$userData)
         ->assertRedirect('/login');

        unset($userData['name']);

        $this->post('/login',$userData)->assertRedirect('/index');      
    }

     /**ユーザー登録できるかテスト */
     public function testregister()
     {
        $userData = factory(User::class)
        ->state('login')
        ->make()
        ->toArray();

         $this->post('/register',$userData)
         ->assertRedirect('/login');

         unset($userData['password']);

         $this->assertDatabaseHas('users',$userData);

         $user = User::firstWhere($userData);

         $this->assertTrue(\Hash::check('password',$user->password));
     }


     /**
      *バリデーションが機能しているかのテスト
      *@dataProvider dataproviderExample
      */
     public function testValidator($name_key,$name_data,$expect)
     {
        $dataList = [$name_key => $name_data];

        $request = new UserRequest();

        $rules = $request->rules();
        $validator = Validator::make($dataList,$rules);
        $vlidation_result = $validator->passes();
        $this->assertEquals($expect,$vlidation_result);

     }
     /** データプロバイダ */
     public function dataproviderExample()
     {
        return [
            '必須エラー' => ['name','',false],
            'email形式エラー' => ['email','aaa',false],
        ];
     }
}
