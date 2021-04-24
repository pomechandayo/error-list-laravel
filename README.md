# ErrorList
Web系エンジニアやプログラマーが解決したエラーを共有するための記事投稿アプリです  

## URL  
トップページに繋がります。ログインせずに検索や記事の閲覧ができます    
http://aws-infra-error-list-laravel.com:10080/index  

全ての機能を使う場合は、お手数ですが下記URLからログインページへと進み「簡単ログイン」ボタンからログインしてください。  
http://aws-infra-error-list-laravel.com:10080/login

## 使用技術
言語: PHP7.4/Javascript/jQuery/Bootstrap    
フレームワーク: Laravel6  
DB: MySQL5.7  
インフラ: AWS(VPC,EC2,Route53)  
ツール: Docker  
Webサーバー: Apache/Amazon Linux  
バージョン管理: Git/Github  
  
## 機能一覧
- 認証機能  
    - ログイン機能  
    - ログアウト機能  
    - 会員登録機能  
    - 簡単ログイン機能  
    - Googleアカウントでのログイン機能(Google API)  
- 記事関連機能  
    - 記事一覧表示機能   
    - 記事詳細表示機能  
    - 記事投稿機能(マークダウン記法対応,トランザクション機能,タグ付け機能)  
    - 記事編集機能(マークダウン記法対応,トランザクション機能,タグ付け機能)  
    - 記事非公開機能
    - 記事削除機能  
    - 高評価機能(Ajax)  
    - ページネーション機能  
    - コメント機能  
        - コメント削除機能  
        - コメント返信機能  
        - コメント返信削除機能  
    - 記事検索機能  
        - タグ検索機能  
        - フリーワード検索機能  
        - タグ&フリーワード検索機能  
- マイページ関連機能  
    - 管理者投稿記事表示  
    - 管理者がコメントした記事表示  
    - プロフィール編集機能(アイコン画像登録機能)  
- 自分以外のユーザーページ  
    - プロフィール表示機能  
    - 投稿一覧表示機能  

## テスト  
- PHPUnit  
    - 単体テスト(Unit)  
    - 統合テスト(Feature)  

## 工夫したところ  
- 記事投稿関連    
    - マークダウン記法で記事投稿できるよう実装した  
    - タグのみの検索、フリーワードのみの検索、タグ&フリーワードで検索できるよう 実装した  
    - 記事投稿機能、編集機能にトランザクション機能を使い実装した  
    - 多対多のリレーション機能を実装した(記事とタグ)  
    - Ajaxで高評価ボタン機能を実装した  
- コーディング    
    - N+1問題を意識してコードを書いた 
    - 命名規則を意識してコーディングした  
    - 関数の引数、返り値に型を宣言した  
    - 記事投稿機能関連をリソースコントローラーで実装した  
- その他    
    - Googleアカウントでログインできるよう実装した    
    - ユーザーアイコン画像を登録できるようにした  
    - インフラにAWSを使った
    - 環境構築にDockerを使った  
    - GitHubのPull requestsやIssuesを使って実践を意識しながらポートフォリオを作った