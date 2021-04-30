

<head>
 
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
  <div id="app">  <!-- `resources/js/app.js`で設定したセレクタ(el)を指定します -->
    <app /> <!-- `resources/js/app.js`で設定したコンポーネントの名称を記述します -->
  </div>
  <script src="{{ mix('js/app.js') }}"></script> <!-- コンパイルされた/public/js/app.jsを読み込みます。コンパイル前のresources/js/app.jsではないことに注意してください -->


