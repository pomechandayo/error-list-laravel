<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="/css/app.css" rel="stylesheet">

  <title>@yield('title') | {{config('app.name','Laravel')}}</title>
</head>
<body>
  <div id="app">  
    <app :auth="{{ Auth::user() ?? '[]' }}" :errors="{{ $errors }}"/> 
    @yield('content')
  </div>
  <script src="{{ mix('js/app.js') }}"></script> 
</body>
</html>
    