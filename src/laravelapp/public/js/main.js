

function selector(select){
  return document.querySelector(select);
}
// 検索アイコンを押すと検索窓を出す
selectSearch = selector('#search');
selectNav = selector('#nav');

selector('.search-btn').onclick = function () {
  if(selectSearch.id == 'search'){
    selectSearch.setAttribute('id','search1');
  }else {
    selectSearch.setAttribute('id','search');
  }
}
// headerのユーザーアイコンをタップすると
// ナビゲーションメニューが出る
selector('.icon-btn').onclick = function () {
  if(selectNav.id == 'nav') {
    selectNav.setAttribute('id','nav1');
  }else {
    selectNav.setAttribute('id','nav');
  }
}

// 読み込んだ画像をimgのsrcに代入する関数
// selector('.edit-profile-input')
// .addEventListener('change',(e)=> {
//   const input= e.target;
//   const reader = new FileReader();
//   reader.onload = (e) => {
//     input.closest('.edit-profile-label1').querySelector('img').src = e.target};
//     reader.readAsDateURL(input.files[0]);
// });

// 記事投稿ページマークダウンエディタ機能
$(function () {
  $('#markdown-editor-textarea').keyup(function () {
    let html = marked($(this).val());
    $('#markdown-preview').html(html);
  });
  });


