# coachtech-frima-app

### coachtechフリマアプリ
アイテムの出品と購入を行うためのフリマアプリ

## 主要機能

- ユーザー登録（MailHogによるメール確認機能付き)・認証
- 商品の閲覧・検索・出品・購入
- Stripe決済（カード・コンビニ）

## 環境構築
### Dockerビルド
1. `git@github.com:yuji-oonaka/coachtech-frima-app.git`
2. `docker-compose up -d --build`

### Laravel環境構築
1. `docker-compose exec php bash`
2. `composer install`
3. `cp .env.example .env`
4. `php artisan key:generate`
5. `php artisan migrate`
6. `php artisan db:seed`
7. `php artisan storage:link`

## mailhogを利用した会員登録に関して
- 会員登録画面にて登録後、ブラウザにてhttp://localhost:8025にアクセス
- 送信されたメールをリアルタイムで確認
- 登録したメールアドレスを選択後、メールアドレスの確認を押すと会員登録が完了しプロフィール設定画面に遷移する

## PHPunitテストに関して
- docker-compose exec bash
1. `cp .env.testing.example .env.testing`
2. `php artisan key:generate --env=testing`
3. `php artisan migrate --env=testing`
4. `php artisan db:seed --env=testing`
5. `php artisan test --testsuite=Feature`

- 個別テストの場合は
- php artisan test tests/Feature/〇〇Test.php

## Stripe決済テストに関して
1. Stripeアカウントの準備
- https://stripe.com/jp
- `Stripe開発者ダッシュボードでアカウント作成
テストモードを有効化（テスト環境の表示を確認）`
- `設定の決済手段にてコンビニ決済を有効化`
2. テストキーの取得
- `ダッシュボード左メニュー → [Developers] → [API keys]
「Standard keys」から以下を取得：
公開可能キー（Publishable key）
シークレットキー（Secret key）`
3. 環境変数の設定
- `.env及び.env.testingに追加`
```
STRIPE_KEY=pk_test_51XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
STRIPE_SECRET=sk_test_51XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
```
4.Stripeパッケージのインストール
- docker-compose exec php bash
- `composer require stripe/stripe-php`

5. 購入画面にて支払い方法選択
- `カード支払いを選択し購入するを押す`
- `stripe決済画面に遷移した後`
- カード番号 `4242 4242 4242 4242`
- 有効期限 `12/34`
- CVC `123`
- 名前 `ヤマダ　タロウ`
- 入力画面に記入後`支払う`を押すと購入が完了となる

## コンビニ支払いテストに関して
- コンビニ決済にてStripe webhookが必要となります

## Webhook設定
-　Stripe CLIのインストール (ローカルにインストール)
開発環境のみでのテストのための一時的な使用です
- ubuntuの場合
```
# 最新版のダウンロード（2025年2月現在）
curl -LO https://github.com/stripe/stripe-cli/releases/download/v1.24.0/stripe_1.24.0_linux_x86_64.tar.gz

# ファイル解凍
tar -xvf stripe_1.24.0_linux_x86_64.tar.gz

# 実行権限付与
chmod +x stripe

# システムパスに配置
sudo mv stripe /usr/local/bin/
```
- macOSではHomebrewでインストールできます
```
brew install stripe/stripe-cli/stripe
```
- `stripe login`
- 表示されたURLにアクセスしアクセスを許可するを押す
- `stripe listen --forward-to http://host.docker.internal/stripe/webhook`
- 生成されたシグネチャシークレットを.env及び.env.testingに追加
`STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXX`

6-2. コンビニ支払いを選択し購入するを押す
- `stripe決済画面に遷移したあと`
- `任意のメールアドレスと名前を入力`支払いをおす

- Ubuntuをもう一つ立ち上げ
- `stripe trigger payment_intent.succeeded`を入力すると即座に購入が完了する


## テストアカウント
name: User1  
email: User1@example.com  
password: password  
- または会員登録にて任意のユーザーを登録

## 開発環境
- 商品一覧画面:http://localhost
- 会員登録画面:http://localhost/register
- phpMyAdmin:http://localhost:8080

## 技術スタック

| 技術 | バージョン |
|------|------------|
| PHP | 8.3.16 |
| Laravel | 11.53.1 |
| MySQL | 8.0.26 |
| nginx | 1.21.1 |
| Stripe | 16.4.0 |
| MailHog | 最新版 |

## ER図
![coachtech-frima-app drawio](https://github.com/user-attachments/assets/27f7b887-9219-471d-a152-ff742308a096)
