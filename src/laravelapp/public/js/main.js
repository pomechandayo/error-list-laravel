

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
selector('.icon-img').onclick = function () {
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

  
  $(function () {
    let like = $('.like-toggle'); 
    let likeReviewId; //変数を宣言（なんでここで？）
    like.on('click', function () { //onはイベントハンドラー
      let $this = $(this); //this=イベントの発火した要素＝iタグを代入
      likeReviewId = $this.data('article-id'); //iタグに仕込んだdata-review-idの値を取得
      //ajax処理スタート
      $.ajax({
        headers: { //HTTPヘッダ情報をヘッダ名と値のマップで記述
          'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },  //↑name属性がcsrf-tokenのmetaタグのcontent属性の値を取得
        url: '/like', //通信先アドレスで、このURLをあとでルートで設定します
        method: 'POST', //HTTPメソッドの種別を指定します。1.9.0以前の場合はtype:を使用。
        data: { //サーバーに送信するデータ
          'article_id': likeReviewId //いいねされた投稿のidを送る
        },
      })
      //通信成功した時の処理
      .done(function (data) {
        $this.toggleClass('liked'); //likedクラスのON/OFF切り替え。
        $this.next('.like-counter').html(data.review_likes_count);
      })
      //通信失敗した時の処理
      .fail(function () {
        console.log('fail'); 
      });
    });
    });
  