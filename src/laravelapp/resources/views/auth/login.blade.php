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
  <h2 class="login-h2">ログイン</h2>
</div>

<div class="register-form" >
<form action="{{ route('login') }}" class="form-box" method="post">
    @csrf
        <h3 style="margin-bottom: 30px;" class="login-h3">簡単ログイン</h3>
        <input type="hidden" name="email" value="error@iya.com">
        <input type="hidden" name="password" value="password">
        <button class="easy-btn" type="submit" onfocus="this.blur(); ">簡単ログイン</button>
        
        
    </form>
    <a href="/login/google" class="google-login" type="button" onfocus="this.blur(); "> Googleからログイン</a>
</div>
    

<form action="{{ route('login') }}" class="register-form" method="post">
    @csrf
<div class="form-box">
    <h3 style="margin-bottom: 20px;" class="login-h3"> 
        メールアドレスでログイン
    </h3>  
        <label for="email">メールアドレス</label>
        <input class="input-box" type="text" name="email" value="{{ old('email')}}" placeholder="@iyatarou@makeruna.com">            
        @if ($errors->has('email'))
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