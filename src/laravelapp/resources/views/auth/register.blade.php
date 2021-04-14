@extends('layouts.app_content')

@section('title')
新規会員登録
@endsection
<style>
    li{list-style-type: none;
        margin-left: 10%;
        color: #ee0000;
    }
    
    
</style>
@section('content')
<div class="relative">
  <h2 class="login-h2">新規会員登録</h2>
</div>


<div class="form-box" style="width: 72%; margin: 0 auto; ">
<form action="{{ route('login') }}" class="register-form" method="post" style="margin: 0; width: 100%;">
    @csrf
    <div class="form-box" style="width: 100%; margin: 0;">
        <h3 style="margin-bottom: 30px; width: 100%;" 
        class="login-h3">簡単ログイン</h3>
        <input type="hidden" name="email" value="error@iya.com">
        <input type="hidden" name="password" value="password">
        <button class="easy-btn" type="submit" onfocus="this.blur(); ">簡単ログイン</button>
    
        <!-- <button class="easy-btn" type="button" onfocus="this.blur(); "> Googleからログイン</button> -->
    
    </div>
</form>

    
    <h3 style="margin-bottom: 20px; width:100%;" class="login-h3"> メールアドレスで登録</h3>
    <form action="{{ route('register') }}" class="register-form" method="post">
        @csrf
        <label for="name" style="margin-left: 10%;">ユーザー名</label>
        <input class="input-box"  type="text" name="name"
        value="{{old('name')}}" placeholder="エラー嫌太郎" style="width: 80%;">
        @if ($errors->has('name'))
        <li class="error-message">{{ $errors->first('name') }}</li>
        @endif    
        
        <label for="email" style="margin-left: 10%;">メールアドレス</label>
        <input class="input-box" type="text" name="email" value="{{ old('email')}}" placeholder="@iyatarou@makeruna.com" style="width: 80%;">
        @if ($errors->has('email'))
        <li class="error-message">{{ $errors->first('email') }}</li>
        @endif
        
        <label for="password" style="margin-left: 10%;">パスワード</label>
        <input class="input-box" type="password" name="password" value="{{ old('password')}}" placeholder="パスワード" style="width: 80%;">
        @if ($errors->has('password'))
        <li class="error-message">{{ $errors->first('password') }}</li>
        @endif
        <button class="register" style="width: 50%;">登録</button>
    </div>
</div>
</form>
@endsection