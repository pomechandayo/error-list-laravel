<template>
<div>
 <div class="relative">
  <h2 class="login-h2">新規会員登録</h2>
</div>

<div class="form-box" style="width: 72%; margin: 0 auto; ">
<form action="login" class="register-form" method="post" style="margin: 0; width: 100%;">
    
    <div class="form-box" style="width: 100%; margin: 0;">
        <h3 style="margin-bottom: 30px; width: 100%;" 
        class="login-h3">簡単ログイン</h3>
        <input type="hidden" name="email" value="error@iya.com">
        <input type="hidden" name="password" value="password">
        <button class="easy-btn" type="submit" onfocus="this.blur(); ">簡単ログイン</button>
    
        <!-- <button class="easy-btn" type="button" onfocus="this.blur(); "> Googleからログイン</button> -->
    
    </div>
</form>
<a href="/login/google" class="google-login" type="button" onfocus="this.blur(); "> Googleからログイン</a>
    
    <h3 style="margin-bottom: 20px; width:100%;" class="login-h3"> メールアドレスで登録</h3>

    <form action="register" class="register-form" method="post">
        <label for="name" style="margin-left: 10%;">ユーザー名</label>
        <input class="input-box"  type="text" name="name"
        value="" placeholder="エラー嫌太郎" style="width: 80%;">
      
        <li class="error-message">エラーメッセージ</li>
        
        
        <label for="email" style="margin-left: 10%;">メールアドレス</label>
        <input class="input-box" type="text" name="email" value="" placeholder="@iyatarou@makeruna.com" style="width: 80%;">
       
        <li class="error-message">エラーメッセージ</li>
     
        
        <label for="password" style="margin-left: 10%;">パスワード</label>
        <input class="input-box" type="password" name="password" value="" placeholder="パスワード" style="width: 80%;">

        <li class="error-message">エラーメッセージ</li>

        <button class="register" style="width: 50%;">登録</button>
</form>
    </div>
</div>
</div>
</template>

<script>
 
</script>