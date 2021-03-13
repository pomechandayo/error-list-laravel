@extends('layouts.app_content')

@section('title')
マイページ/プロフィール編集
@endsection
@section('content')
@if(session('status')) 
  <div class="edit-profile-message">
    {{session('status')}}
  </div>
@endif
  <div class="edit-profile-box">


    <h2 class="edit-profile-h2">プロフィール編集</h2>
    <div class="edit-profile-border"></div>
    <form action="{{ route('mypage.edit-profile') }}" method="post">
    @csrf
    <input type="file" name="avatar" accept="image/*" class="edit-profile-input">
    <label for="avater">
      <img class="avatar-img" src="{{ asset('/img/avatar1.png') }}" alt="自分のプロフィールに表示される画像です">
    </label>

    <label for="name" class="edit-profile-label">ニックネーム</label>
    <input type="text" class="edit-profile-name" name="name"
    value="{{ old('name',$user->name) }}">
    @if ($errors->has('name'))
      <li class="error-message">{{ $errors->first('name') }}</li>
    @endif 

    <button type="submit" class="edit-profile-save">保存</button>

    </form>
  </div>
@endsection