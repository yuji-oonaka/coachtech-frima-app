# coachtech-frima-app
### coachtechフリマアプリ
アイテムの出品と購入を行うためのフリマアプリ

## 環境構築
### Dockerビルド
1. `git@github.com:yuji-oonaka/coachtech-frima-app.git`
2. `docker-compose up -d --build`

### Laravel環境構築
1. `docker-compose exec php bash`
2. `composer install`
3. `cp .env.example .env`
4. `php artisan key:generate`
5. `php artisan storage:link`
6. `php artisan migrate`
7. `php artisan db:seed`

### mailhogを利用した会員登録に関して
- 会員登録画面にて登録後、ブラウザにてhttp://localhost:8025にアクセス
- 送信されたメールをリアルタイムで確認
- 登録したメールアドレスを選択後、メールアドレスの確認を押すと会員登録が完了しプロフィール設定画面に遷移する

### PHPunitテストに関して
- docker-compose exec bash
1. `cp .env.testing.example .env.testing`
2. `php artisan key:generate --env=testing`
3. `php artisan migrate --env=testing`
4. `php artisan db:seed --env=testing`
5. `php artisan test --testsuite=Feature`

- 個別テストの場合は
-php artisan test tests/Feature/〇〇.php

### Stripe決済に関して
1. Stripeアカウントの準備
Stripe開発者ダッシュボードでアカウント作成
テストモードを有効化（ダッシュボード右上の「TEST DATA」表示を確認）
2. テストキーの取得
ダッシュボード左メニュー → [Developers] → [API keys]
「Standard keys」から以下を取得：
公開可能キー（Publishable key）
シークレットキー（Secret key）

3. 環境変数の設定
.env及び.env.testingに追加
STRIPE_KEY=pk_test_51XXXXXXXXXXXXXXX
STRIPE_SECRET=sk_test_51XXXXXXXXXXXXXXX
STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXX

4. Webhook設定
stripe listen --forward-to localhost:8000/stripe/webhook
生成されたシグネチャシークレットを.env及び.env.testingに追加

5.支払い方法選択
- カード支払いを選択
stripe決済画面に遷移した後
- カード番号 `4242 4242 4242 4242`
- 有効期限 `12/34`
- CVC `123`
- 名前 `ヤマダ　タロウ`
- 入力画面に記入後`支払う`を押すと購入が完了となる

コンビニ支払いを選択した場合
- webhookを使用
-3分後購入が完了となる

## テストアカウント
name: User1
email: User1@example.com
password: password
- または会員登録にて任意のユーザーを登録

## 開発環境
- 商品一覧画面:http://localhost
- 会員登録画面:http://localhost/register
- phpMyAdmin:http://localhost:8080

## 使用技術
- PHP:8.3.16
- Laravel:11.53.1
- MySQL:8.0.26
- nginx:1.21.1
- stripe:16.4.0
- mailhog

## ER図
![coachtech-frima-app drawio](https://github.com/user-attachments/assets/27f7b887-9219-471d-a152-ff742308a096)
