# ErrorList
Web系エンジニアやプログラマーがエラーを共有するための記事投稿アプリです  

## URL  
http://aws-infra-error-list-laravel.com:10080/index  
トップページに繋がります。ログイン無しでも検索や記事の閲覧はできます。
投稿機能やいいね機能を使う場合は、お手数ですがログインページの「簡単ログイン」ボタンからログインしてください。

## 使用技術
言語:PHP7.4/Javascript/jQuery/Bootstrap/CSS/HTML  
フレームワーク:Laravel6  
データベース:MySQL5.7  
インフラ:AWS(VPC,EC2,Route53)/Docker/Docker-Compose  
Webサーバー:Apache/Amazon Linux/HTTP  
バージョン管理:Git/Github
  
## 機能一覧
- 認証機能  
    - ログイン機能  
    - ログアウト機能  
    - 会員登録機能 
    - 簡単ログイン機能(ログインページにある)
- 検索機能　　
    - タグ検索機能  
    - フリーワード検索機能  
    - タグ&フリーワード検索機能  
- 記事投稿機能
- いいね機能(Ajax)  
- コメント機能  
- コメント返信機能  
- ページネーション機能  

## テスト  
- PHPUnit  
    - 単体テスト(Unit)  
    - 統合テスト(Feature)  

## 工夫したところ  
- N+1問題を意識してコードを書いた  
- Ajaxでいいいね機能を実装した  
- タグのみの検索、フリーワードのみの検索、タグ&フリ-ワードで検索できるよう実装した
- インフラにAWS,Dcokerを使った  
