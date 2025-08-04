coachtechフリマ
アイテムの出品と購入を行うためのフリマアプリ

目次
主要機能
環境構築
Dockerビルド
Laravel環境構築
mailhogを利用した会員登録
Stripe決済テスト
コンビニ支払いテスト
PHPunitテスト
テストアカウント
開発環境
技術スタック
ER図
主要機能
ユーザー登録（MailHogによるメール確認機能付き)・認証
商品の閲覧・検索・出品・購入
Stripe決済（カード・コンビニ）
目次に戻る

環境構築
Dockerビルド
git clone git@github.com:yuji-oonaka/coachtech-frima-app.git
docker-compose up -d --build
Laravel環境構築
docker-compose exec php bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
目次に戻る

mailhogを利用した会員登録に関して
会員登録画面にて登録後、ブラウザにて http://localhost:8025 にアクセス
送信されたメールをリアルタイムで確認
登録したメールアドレスを選択後、メールアドレスの確認を押すと会員登録が完了しプロフィール設定画面に遷移する
目次に戻る

Stripe決済テストに関して
Stripeアカウントの準備
https://stripe.com/jp
Stripe開発者ダッシュボードでアカウント作成 テストモードを有効化（テスト環境の表示を確認）
設定の決済手段にてコンビニ決済を有効化
テストキーの取得
ダッシュボード左メニュー → [Developers(開発者)] → [API keys] 「Standard keys(標準キー)」から以下を取得：

公開可能キー（Publishable key）
シークレットキー（Secret key）
環境変数の設定
.envに追加

STRIPE_KEY=pk_test_51XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
STRIPE_SECRET=sk_test_51XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

購入画面にて支払い方法選択
カード支払いを選択し購入するを押す
stripe決済画面に遷移した後
メールアドレス 任意のメールアドレス
カード番号 4242 4242 4242 4242
有効期限 12/34
CVC 123
名前 任意の名前
入力画面に記入後支払うを押すと購入が完了となる
目次に戻る

コンビニ支払いテストに関して
コンビニ決済にてStripe webhookを使用します

Webhook設定
docker-compose exec php bash
stripe login
表示されたURLにアクセスしアクセスを許可するを押す
stripe listen --forward-to http://host.docker.internal/stripe/webhook
生成されたシグネチャシークレットを.envに追加
STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXX

コンビニ支払いを選択し購入するを押す

stripe決済画面に遷移したあと

メールアドレス succeed_immediately@test.com
名前 任意の名前
支払いを押す
Note

メールアドレス：succeed_immediately@test.com

このメールアドレスを使用することで、テスト環境でコンビニ支払いのフローを正しくシミュレートできます。このアドレスは、Stripeのテスト環境で即時に成功する支払いをトリガーします。

目次に戻る

PHPunitテストに関して
docker-compose exec php bash
cp .env.testing.example .env.testing
php artisan key:generate --env=testing
php artisan migrate --env=testing
php artisan db:seed --env=testing
php artisan test --testsuite=Feature
個別テストの場合は
php artisan test tests/Feature/〇〇Test.php
Note

.env.testingにもStripeのAPIキーを設定してください

目次に戻る

テストアカウント
name: User1
email: User1@example.com
password: password
または会員登録にて任意のユーザーを登録

目次に戻る

開発環境
商品一覧画面:http://localhost
会員登録画面:http://localhost/register
phpMyAdmin:http://localhost:8080
phpMyAdmin_testing:http://localhost:8081
目次に戻る

技術スタック
技術	バージョン
PHP	8.3.17
Laravel	11.53.1
MySQL	8.0.26
nginx	1.21.1
Stripe	16.4.0
MailHog	最新版
目次に戻る

ER図
coachtech-frima-app drawio

目次に戻る
