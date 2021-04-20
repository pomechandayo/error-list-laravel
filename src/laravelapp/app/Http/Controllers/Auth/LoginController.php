<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use IlluminateContracts\Auth\Authenticatable;
use Socialite;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectPath()
    {
        return 'index';
    }

    public function redirectToGoogle()
    {
        // Googleへのリダイレクト
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback()
    {
        // Google 認証後の処理
        // dd()で取得するユーザーを情報を確認
        $gUser = Socialite::driver('google')->stateless()->user();
        // emailが合致するユーザーを取得
        $user = User::where('email',$gUser->email)->first();
        // 見つからなければユーザーを作成
        if($user === null)
        {
            $user = $this->createUserByGoogle($gUser);
        }

        \Auth::login($user,true);
        return redirect('/index');

    }
     public function createUserByGoogle($gUser)
    {
          $user = User::create([
              'name' => $gUser->name,
              'email' => $gUser->email,
              'password' => \Hash::make(uniqid()),
          ]);
    }
}
