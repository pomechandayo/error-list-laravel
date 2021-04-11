<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserRequest;
use Tests\TestCase;
use App\User;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    /**ユーザー登録ページ表示テスト */
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
         $userData = $this->userData();
 
         $this->post('register',$userData)
         ->assertStatus(302);

         unset($userData['password']);

         $this->assertDatabaseHas('users',$userData);

         $user = User::firstWhere($userData);

         $this->assertTrue(\Hash::check('abcd1234',$user->password));
     }


     /**
      *
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
