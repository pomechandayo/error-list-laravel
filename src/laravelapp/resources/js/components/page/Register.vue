<template>
<div>
 <div class="relative">
  <h2 class="login-h2">新規会員登録</h2>
</div>

<div class="form-box-m" style="width: 72%;">

<form 
method="post" 
action="login" 
class="form-box-s" 
style="width: 100%;"
>
        <h3 class="login-easy-btn">簡単ログイン</h3>

        <input type="hidden" name="_token" :value="csrf" />
        <input type="hidden" name="email" value="error@iya.com">
        <input type="hidden" name="password" value="password">
        <button class="easy-btn" type="submit" onfocus="this.blur(); ">簡単ログイン</button>
    
        <!-- <button class="easy-btn" type="button" onfocus="this.blur(); "> Googleからログイン</button> -->
    
</form>

<!-- <a href="/login/google" class="google-login" type="button" onfocus="this.blur(); "> Googleからログイン</a> -->
    
    <h3 class="login-h3"> メールアドレスで登録</h3>

<div class="" v-if="errors.length !== 0">
  <li class="">
   <div class="validation-message" v-for="(error, key, index) in errors" :key="index">
   {{error}}</div>
  </li>

</div>
    <ValidationObserver
    action="/register" 
    class="register-form" 
    method="post"
    id="register"
    tag="form"
    ref="observer"
    v-slot="{ invalid }"
    >
        <input type="hidden" name="_token" :value="csrf" />
        <label class=auth-label for="name">ユーザー名</label>

        <validation-provider 
        class="validation-v"
         name="名前" 
         rules="requiredmax:20" 
         v-slot="{ errors }"
         >
          <input v-model="name" class="input-box"  type="text" name="name" value="" placeholder="エラー嫌太郎" />
      
          <li class="error-message " v-show="errors[0]" >
           {{ errors[0] }}
          </li>

        </validation-provider>
        
            <label class=auth-label for="email">メールアドレス</label>

         <validation-provider class="validation-v" name="メールアドレス" rules="requiredemail" v-slot="{ errors }"> 
            <input class="input-box" type="text" name="email" value="" placeholder="iyatarou@makeruna.com">
            <li class="error-message" v-show="errors[0]">{{ errors[0] }}</li>

         </validation-provider>
          
        
        <label class=auth-label for="password">パスワード</label>
        
        <validation-provider 
        class="validation-v"
        name="パスワード" 
        rules="requiredmin:8" 
        v-slot="{ errors }"
        > 
          <input class="input-box" type="password" name="password" value="" placeholder="パスワード">

          <li class="error-message" v-show="errors[0]">{{ errors[0]}}</li>

        </validation-provider>

        <button class="register">登録</button>

</ValidationObserver>
    </div>
</div>
</div>
</template>

<script>

import {ValidationProvider,ValidationObserver,extend} from "vee-validate";
import { required,max,min,email,confirmed} from "vee-validate/dist/rules";

export default {
  components: {
    ValidationProvider,
    ValidationObserver
  },

  data() {
    return{
      name:"",
      email:"",
      password:"",

      csrf: document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content"),
    };
  },
  props: {
    errors: {
      type: Array|Object
    }
  },
  methods: {
    async register() {
      const isValid = await this.$refs.observer.validate();
      if(isValid) {
        document.querySelector("#register").submit();
      }
    }
  }
};
</script>