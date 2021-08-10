<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserRequest;
use App\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     *
     * ページを表示できるか  
     */ 
    public function testShowPage()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     *
     * ログインできるか
     */ 
    public function testLogin()
    {
        $userData = factory(User::class)
        ->state('login')
        ->make()
        ->toArray();

        $this->post('/register',$userData);
        unset($userData['name']);

        $this->post('/login',$userData)->assertRedirect('/index');
        
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
    /**
     *データプロバイダ 
     *@return array
    */
    public function dataproviderExample() :array
    {
         return [
           'email形式エラー' => ['email','error@iya.com',false],
           '必須エラー' => ['password','password',false],
         ];
    }


}