

function selector(select){
  return document.querySelector(select);
}
// 検索アイコンを押すと検索窓を出す(header)
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


// 記事投稿ページマークダウンエディタ機能
$(function () {
  $('#markdown-editor-textarea').keyup(function () {
    let html = marked($(this).val());
    $('#markdown-preview').html(html);
  });
  });

  