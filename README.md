こちらをMarkdown形式で整形しました。READMEとして利用できる体裁になっています。  

***

# coachtechフリマ  
アイテムの出品と購入を行うためのフリマアプリ  

## 目次
- [主要機能](#主要機能)  
- [環境構築](#環境構築)  
  - [Dockerビルド](#dockerビルド)  
  - [Laravel環境構築](#laravel環境構築)  
- [MailHogを利用した会員登録](#mailhogを利用した会員登録に関して)  
- [Stripe決済テスト](#stripe決済テストに関して)  
- [コンビニ支払いテスト](#コンビニ支払いテストに関して)  
- [PHPUnitテスト](#phpunitテストに関して)  
- [テストアカウント](#テストアカウント)  
- [開発環境](#開発環境)  
- [技術スタック](#技術スタック)  
- [ER図](#er図)  

***

## 主要機能
- ユーザー登録（MailHogによるメール確認機能付き）・認証  
- 商品の閲覧・検索・出品・購入  
- Stripe決済（カード・コンビニ）  

***

## 環境構築

### Dockerビルド
```bash
git clone git@github.com:yuji-oonaka/coachtech-frima-app.git
docker-compose up -d --build
```

### Laravel環境構築
```bash
docker-compose exec php bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
```

***

## MailHogを利用した会員登録に関して
- 会員登録画面にて登録後、ブラウザで [http://localhost:8025](http://localhost:8025) にアクセス  
- 送信されたメールを確認し、メール内の「メールアドレス確認」を押すと登録完了  
- プロフィール設定画面に遷移する  

***

## Stripe決済テストに関して

### Stripeアカウントの準備  
- [Stripe公式](https://stripe.com/jp) でアカウント作成  
- 開発者ダッシュボードで「テストモード」を有効化  
- コンビニ決済を有効化  

### APIキー取得 & `.env`設定  
```
STRIPE_KEY=pk_test_51XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
STRIPE_SECRET=sk_test_51XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
```

### カード決済テスト情報  
- カード番号: 4242 4242 4242 4242  
- 有効期限: 12/34  
- CVC: 123  
- メールアドレス・名前: 任意  

支払いを完了すると購入成功。  

***

## コンビニ支払いテストに関して

### Webhook設定
```bash
docker-compose exec php bash
stripe login
stripe listen --forward-to http://host.docker.internal/stripe/webhook
```
生成されたシグネチャシークレットを`.env`に追加:
```
STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXX
```

### テスト情報  
- メールアドレス: succeed_immediately@test.com  
- 名前: 任意  

このテストメールアドレスを使うと即時に成功する決済をシミュレート可能。  

***

## PHPUnitテストに関して
```bash
docker-compose exec php bash
cp .env.testing.example .env.testing
php artisan key:generate --env=testing
php artisan migrate --env=testing
php artisan db:seed --env=testing
php artisan test --testsuite=Feature
```

個別テスト実行:
```bash
php artisan test tests/Feature/〇〇Test.php
```

Note: `.env.testing` にも Stripe API キーを設定してください。  

***

## テストアカウント
- name: User1  
- email: User1@example.com  
- password: password  

または新規会員登録で任意のユーザーを作成。  

***

## 開発環境
- 商品一覧画面: [http://localhost](http://localhost)  
- 会員登録画面: [http://localhost/register](http://localhost/register)  
- phpMyAdmin: [http://localhost:8080](http://localhost:8080)  
- phpMyAdmin_testing: [http://localhost:8081](http://localhost:8081)  

***

## 技術スタック
| 技術       | バージョン  |
|------------|------------|
| PHP        | 8.3.17     |
| Laravel    | 11.53.1    |
| MySQL      | 8.0.26     |
| nginx      | 1.21.1     |
| Stripe     | 16.4.0     |
| MailHog    | 最新版      |

***

## ER図
`coachtech-frima-app.drawio`  

***

この内容をREADME.mdとして使える体裁にしました。  
追加で「画面キャプチャ」や「実行手順フロー図」も追記しますか？
