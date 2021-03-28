@extends('layouts.app_content')

@section('title')
ログイン
@endsection
<style>
    li{list-style-type: none;
        margin-left: 10%;
        color: #ee0000;
    }
</style>
@section('content')
<div class="relative">
  <h2>ログイン</h2>
</div>
<form action="{{ route('login') }}" class="register-form" method="post">
    @csrf

  <div class="form-box">
      <h3 style="margin-bottom: 30px;">簡単ログイン</h3>
        <a href="" class="easy-login-btn"><button class="easy-btn" type="button" onfocus="this.blur(); "> こちらから会員登録無しで全ての機能を使うことができます</button></a>

        <a href="" class="easy-login-btn"><button class="easy-btn" type="button" onfocus="this.blur(); "> Googleからログイン</button>
        </a>

    <h3 style="margin-bottom: 20px;"> 
        メールアドレスで登録
    </h3>  
        <label for="email">メールアドレス</label>
        <input class="input-box" type="text" name="email" value="{{ old('email')}}" placeholder="@iyatarou@makeruna.com">            @if ($errors->has('email'))
            <li class="error-message">{{ $errors->first('email') }}</li>
        @endif
            <label for="password">パスワード</label>
            <input class="input-box" type="password" name="password" value="{{ old('password')}}" placeholder="パスワード">
         @if ($errors->has('password'))
            <li class="error-message">{{ $errors->first('password') }}</li>
        @endif
        
            <button type="submit" class="register" >ログイン</button>
            <div class="make-acount">
                <a class="make-acount-link" href="{{ route('register') }}">アカウントをお持ちでない方はこちら</a>
            </div>
            <div class="forget-password">
                <a class="forget-password-link" href="{{ route('password.request') }}">パスワードをお忘れの方はこちら</a>
            </div>
  </div>
</form>
@endsection