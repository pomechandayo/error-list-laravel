

<head>
 
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>
  <div id="app">
    <router-view /> 
  </div>
  <script src="{{ mix('js/app.js') }}"></script> <!-- コンパイルされた/public/js/app.jsを読み込みます。コンパイル前のresources/js/app.jsではないことに注意してください -->


