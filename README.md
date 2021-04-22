# ErrorList
Web系エンジニアやプログラマーが解決したエラーを共有するための記事投稿アプリです  

## URL  
トップページに繋がります。ログインせずに検索や記事の閲覧ができます    
http://aws-infra-error-list-laravel.com:10080/index  

全ての機能を使う場合は、お手数ですが下記URLからログインページへと進み「簡単ログイン」ボタンからログインしてください。  
http://aws-infra-error-list-laravel.com:10080/login

## 使用技術
言語: PHP7.4/Javascript/jQuery/Bootstrap/CSS/HTML  
フレームワーク: Laravel6  
DB: MySQL5.7  
インフラ: AWS(VPC,EC2,Route53)  
ツール: Docker/Docker-Compose  
Webサーバー: Apache/Amazon Linux/HTTP  
バージョン管理: Git/Github  
  
## 機能一覧
- 認証機能  
    - ログイン機能  
    - ログアウト機能  
    - 会員登録機能 
    - 簡単ログイン機能  
    - Googleアカウントでのログイン機能  
- 検索機能　　
    - タグ検索機能  
    - フリーワード検索機能  
    - タグ&フリーワード検索機能  
- 記事投稿機能(マークダウン記法対応)  
- 高評価機能(Ajax)  
- コメント機能  
- コメント返信機能  
- ページネーション機能  

## テスト  
- PHPUnit  
    - 単体テスト(Unit)  
    - 統合テスト(Feature)  

## 工夫したところ  
- N+1問題を意識してコードを書いた 
- Ajaxで高評価ボタン機能を実装した  
- Googleアカウントでログインできるよう実装した    
- マークダウン記法で記事投稿できるよう実装した    
- 記事投稿機能、編集機能にトランザクション機能を使い実装した  
- タグのみの検索、フリーワードのみの検索、タグ&フリーワードで検索できるよう 実装した  
- 記事投稿機能関連をリソースコントローラーで実装した  
- インフラにAWSを使った
- 環境構築にDockerを使った  
- GitHubのPull requestsやIssuesを使って実践を意識しながらポートフォリオを作った  
- 命名規則を意識してコーディグした
