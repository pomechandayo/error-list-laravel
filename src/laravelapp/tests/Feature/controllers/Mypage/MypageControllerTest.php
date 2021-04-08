<?php

namespace Tests\Feature\Controllers\Mypage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

/**
 * namespace App\Http\Controllers\MyPage\ProfileController
 */


class MypageControllerTest extends TestCase
{
      /**認証されているユーザーだけがMypage表示されるかテスト */
   public function testProfile()
   {  
        // 認証していない場合
       $this->get('/mypage/profile')
       ->assertRedirect('/login');

       $user = User::find(1)->first();

       $this->actingAs($user)
       ->get('/mypage/profile')
       ->assertOk();
         

   }
}
