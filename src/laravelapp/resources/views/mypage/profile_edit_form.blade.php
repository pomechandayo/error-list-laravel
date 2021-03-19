@extends('layouts.app_content')

@section('title')
プロフィール編集
@endsection
<head>
<link rel="stylesheet" href="{{ asset('/css/mypage.css') }}">
</head>

@section('content')
@if(session('status')) 
  <div class="edit-profile-message">
    {{session('status')}}
  </div>
@endif
  <div class="edit-profile-box">
    
    <h2 class="edit-profile-h2">プロフィール編集</h2>
    <div class="edit-profile-border"></div>
    
    <form action="{{ route('mypage.edit-profile') }}" method="post" enctype="multipart/form-data">
    @csrf
    <!-- プロフィール画像 -->
    <label for="profile_image" class="edit-profile-label1">
      <input 
        type="file" 
        name="profile_image" 
        accept="image/png,image/jpeg,image/gif" class="edit-profile-input" id="profile_image" style="display: none;;" />
        @if(!empty($user->profile_image))
          <img src="/storage/profile_image/{{$user->profile_image}}" alt="プロフィールの画像です" class="profile_img">
        @else
          <img src="{{ asset('/img/default_image.png')}}" alt="プロフィールの画像です" class="profile_img">
        @endif
        
     </label>
    <label for="name" class="edit-profile-label2">ニックネーム</label>
    <input 
      type="text" 
      class="edit-profile-name" 
      name="name"
    
      value="{{ old('name',$user->name) }}">
    @if ($errors->has('name'))
      <li class="error-message">{{ $errors->first('name') }}</li>
    @endif 

    <button type="submit" class="edit-profile-save">保存</button>

    </form>
  </div>
@endsection