SHARE (仮) - ソシャルメディア風アプリケーション
短いメッセージを投稿・共有するための、シンプルなSNS風アプリケーションです。フロントエンドはNext.js、バックエンドはLaravelで構築され、認証にはFirebaseを利用しています。

目次
主要機能

技術スタック

環境構築

Firebaseのセットアップ

テストの実行

テストアカウント

ER図（参考）

主要機能
ユーザー認証: Firebase Authenticationを利用した会員登録、ログイン、ログアウト機能

投稿: テキストメッセージの投稿、表示、削除（本人のみ）

インタラクション: 各投稿への「いいね」機能（登録・解除）、コメント機能

技術スタック
カテゴリ	技術・サービス
フロントエンド	Next.js, React, TypeScript, Tailwind CSS
バックエンド	Laravel, PHP, MySQL
認証	Firebase Authentication
開発環境	Laravel Sail (Docker)
テスト	Jest, React Testing Library (フロントエンド)<br>PHPUnit (バックエンド)

Google スプレッドシートにエクスポート
環境構築
本プロジェクトはフロントエンドとバックエンドのリポジトリが分かれていることを想定しています。

1. バックエンド (Laravel) の構築
Bash

# 1. リポジトリをクローン
git clone <バックエンドのリポジトリURL>
cd <バックエンドのディレクトリ>

# 2. 環境変数の設定
cp .env.example .env

# 3. .envファイルを編集し、データベース接続情報とFirebaseの秘密鍵パスを設定
# DB_CONNECTION=mysql
# DB_HOST=mysql
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=sail
# DB_PASSWORD=password
# FIREBASE_CREDENTIALS=/var/www/html/path/to/your/firebase_credentials.json

# 4. Dockerコンテナを起動
./vendor/bin/sail up -d

# 5. 依存パッケージをインストール
./vendor/bin/sail composer install

# 6. アプリケーションキーを生成
./vendor/bin/sail artisan key:generate

# 7. データベースを構築し、テストデータを投入
./vendor/bin/sail artisan migrate:fresh --seed
バックエンドは http://localhost で起動します。

2. フロントエンド (Next.js) の構築
Bash

# 1. リポジトリをクローン
git clone <フロントエンドのリポジトリURL>
cd <フロントエンドのディレクトリ>

# 2. 依存パッケージをインストール
npm install

# 3. 環境変数の設定
cp .env.local.example .env.local

# 4. .env.localファイルを編集し、Firebase設定とバックエンドAPIのURLを設定
# NEXT_PUBLIC_FIREBASE_API_KEY="..."
# NEXT_PUBLIC_FIREBASE_AUTH_DOMAIN="..."
# ... (その他のFirebase設定) ...
# NEXT_PUBLIC_API_BASE_URL="http://localhost/api"

# 5. 開発サーバーを起動
npm run dev
フロントエンドは http://localhost:3000 で起動します。

Firebaseのセットアップ
Firebaseプロジェクトの作成: Firebaseコンソールで新しいプロジェクトを作成します。

ウェブアプリの登録: プロジェクト設定からウェブアプリを登録し、表示されるfirebaseConfigオブジェクトの値をフロントエンドの.env.localにコピーします。

Authenticationの有効化: Authenticationサービスへ移動し、「メール/パスワード」によるサインインを有効にします。

サービスアカウント秘密鍵の取得: プロジェクト設定の「サービスアカウント」タブへ移動し、「新しい秘密鍵の生成」をクリックしてJSONファイルをダウンロードします。このファイルをバックエンドプロジェクト内の安全な場所に配置し、そのコンテナ内パスをバックエンドの.envファイルにあるFIREBASE_CREDENTIALSに設定します。

テストの実行
バックエンドテスト (PHPUnit)
バックエンドのAPIが正しく機能するかをテストします。

Bash

# バックエンドのディレクトリで実行
./vendor/bin/sail artisan test
フロントエンドテスト (Jest)
フロントエンドのコンポーネントが正しく表示・動作するかをテストします。

Bash

# フロントエンドのディレクトリで実行
npm test
テストアカウント
シーディングを実行すると、以下のテスト用アカウントが作成されます。

メールアドレス: test@example.com

パスワード: password

ER図（参考）
本アプリケーションの主要なデータベース構成です。
| テーブル名 | 概要 |
| :--- | :--- |
| users | ユーザー情報（FirebaseのUIDを含む） |
| posts | 投稿内容 |
| comments | 投稿へのコメント |
| likes | 投稿への「いいね」 |







最初のタイトルから目次主要機能などもすべて貼り付けられるように

一貫していただけますか



例としてはこのような感じです



# coachtech-frima-app



coachtechフリマ  

アイテムの出品と購入を行うためのフリマアプリ



## 目次



1. [主要機能](#主要機能)

2. [環境構築](#環境構築)

   - [Dockerビルド](#dockerビルド)

   - [Laravel環境構築](#laravel環境構築)

   - [mailhogを利用した会員登録](#mailhogを利用した会員登録に関して)

   - [Stripe決済テスト](#stripe決済テストに関して)

   - [コンビニ支払いテスト](#コンビニ支払いテストに関して)

   - [PHPunitテスト](#phpunitテストに関して)

3. [テストアカウント](#テストアカウント)

4. [開発環境](#開発環境)

5. [技術スタック](#技術スタック)

6. [ER図](#er図)



## 主要機能



- ユーザー登録（MailHogによるメール確認機能付き)・認証

- 商品の閲覧・検索・出品・購入

- Stripe決済（カード・コンビニ）



[目次に戻る](#目次)



## 環境構築

### Dockerビルド

1. `git clone git@github.com:yuji-oonaka/coachtech-frima-app.git`

2. `docker-compose up -d --build`



### Laravel環境構築

1. `docker-compose exec php bash`

2. `composer install`

3. `cp .env.example .env`

4. `php artisan key:generate`

5. `php artisan migrate`

6. `php artisan db:seed`

7. `php artisan storage:link`  



[目次に戻る](#目次)



## mailhogを利用した会員登録に関して



1. 会員登録画面にて登録後、ブラウザにて http://localhost:8025 にアクセス

2. 送信されたメールをリアルタイムで確認

3. 登録したメールアドレスを選択後、メールアドレスの確認を押すと会員登録が完了しプロフィール設定画面に遷移する



[目次に戻る](#目次)



## Stripe決済テストに関して



#### Stripeアカウントの準備



1. https://stripe.com/jp

2. Stripe開発者ダッシュボードでアカウント作成 テストモードを有効化（テスト環境の表示を確認）

3. 設定の決済手段にてコンビニ決済を有効化



#### テストキーの取得



ダッシュボード左メニュー → [Developers(開発者)] → [API keys] 「Standard keys(標準キー)」から以下を取得：

- 公開可能キー（Publishable key）

- シークレットキー（Secret key）



#### 環境変数の設定



.envに追加



STRIPE_KEY=pk_test_51XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX  

STRIPE_SECRET=sk_test_51XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX



#### 購入画面にて支払い方法選択



1. カード支払いを選択し購入するを押す

2. stripe決済画面に遷移した後

   - メールアドレス `任意のメールアドレス`

   - カード番号 `4242 4242 4242 4242`

   - 有効期限 `12/34`

   - CVC `123`

   - 名前  `任意の名前`

4. 入力画面に記入後支払うを押すと購入が完了となる



[目次に戻る](#目次)



## コンビニ支払いテストに関して



コンビニ決済にてStripe webhookを使用します



### Webhook設定



- `docker-compose exec php bash`

- `stripe login`

- 表示されたURLにアクセスしアクセスを許可するを押す

- `stripe listen --forward-to http://host.docker.internal/stripe/webhook`



生成されたシグネチャシークレットを.envに追加  

STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXX



コンビニ支払いを選択し購入するを押す



stripe決済画面に遷移したあと

- メールアドレス `succeed_immediately@test.com`

- 名前 `任意の名前`

- 支払いを押す

> [!NOTE]

> メールアドレス：succeed_immediately@test.com



このメールアドレスを使用することで、テスト環境でコンビニ支払いのフローを正しくシミュレートできます。このアドレスは、Stripeのテスト環境で即時に成功する支払いをトリガーします。



[目次に戻る](#目次)



## PHPunitテストに関して

- docker-compose exec php bash

1. `cp .env.testing.example .env.testing`

2. `php artisan key:generate --env=testing`

3. `php artisan migrate --env=testing`

4. `php artisan db:seed --env=testing`

5. `php artisan test --testsuite=Feature`



- 個別テストの場合は

- `php artisan test tests/Feature/〇〇Test.php`



> [!NOTE]

> .env.testingにもStripeのAPIキーを設定してください



[目次に戻る](#目次)



## [テストアカウント](pplx://action/followup)



- name: User1

- email: User1@example.com

- password: password



または会員登録にて任意のユーザーを登録



[目次に戻る](#目次)



## [開発環境](pplx://action/followup)



- 商品一覧画面:http://localhost

- 会員登録画面:http://localhost/register

- phpMyAdmin:http://localhost:8080

- phpMyAdmin_testing:http://localhost:8081



[目次に戻る](#目次)



## [技術スタック](pplx://action/followup)



| 技術 | バージョン |

|------|------------|

| PHP | 8.3.17 |

| Laravel | 11.53.1 |

| MySQL | 8.0.26 |

| nginx | 1.21.1 |

| Stripe | 16.4.0 |

| MailHog | 最新版 |



[目次に戻る](#目次)



## ER図

![coachtech-frima-app drawio](https://github.com/user-attachments/assets/086152e8-ecf6-4e06-8306-1555ccbe8126)



[目次に戻る](#目次)




思考プロセスを表示
はい、承知いたしました。
ご提示いただいた例のように、詳細な目次やセクションごとの「目次に戻る」リンクなどを含んだ、より丁寧で一貫性のあるREADMEを作成します。

以下の---で囲まれた部分を、そのままコピーしてREADME.mdファイルに貼り付けてください。

SHARE (仮) - ソーシャルメディア風アプリケーション
短いメッセージを投稿・共有するための、シンプルなSNS風アプリケーションです。フロントエンドはNext.js、バックエンドはLaravelで構築され、認証にはFirebaseを利用しています。

目次
主要機能

技術スタック

環境構築

バックエンド (Laravel)

フロントエンド (Next.js)

Firebaseのセットアップ

テストの実行

バックエンドテスト (PHPUnit)

フロントエンドテスト (Jest)

テストアカウント

ER図（参考）

主要機能
ユーザー認証: Firebase Authenticationを利用した会員登録、ログイン、ログアウト機能

投稿: テキストメッセージの投稿、表示、削除（本人のみ）

インタラクション: 各投稿への「いいね」機能（登録・解除）、コメント機能

▲ 目次に戻る

技術スタック
カテゴリ	技術・サービス
フロントエンド	Next.js, React, TypeScript, Tailwind CSS
バックエンド	Laravel, PHP, MySQL
認証	Firebase Authentication
開発環境	Laravel Sail (Docker)
テスト	Jest, React Testing Library (フロントエンド)<br>PHPUnit (バックエンド)

Google スプレッドシートにエクスポート
▲ 目次に戻る

環境構築
本プロジェクトはフロントエンドとバックエンドのリポジトリが分かれていることを想定しています。

1. バックエンド (Laravel) の構築
リポジトリをクローン

Bash

git clone <バックエンドのリポジトリURL>
cd <バックエンドのディレクトリ>
環境変数の設定

Bash

cp .env.example .env
.envファイルを編集し、データベース接続情報とFirebaseの秘密鍵パスを設定

コード スニペット

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
FIREBASE_CREDENTIALS=/var/www/html/path/to/your/firebase_credentials.json
Dockerコンテナを起動

Bash

./vendor/bin/sail up -d
依存パッケージをインストール

Bash

./vendor/bin/sail composer install
アプリケーションキーを生成

Bash

./vendor/bin/sail artisan key:generate
データベースを構築し、テストデータを投入

Bash

./vendor/bin/sail artisan migrate:fresh --seed
バックエンドは http://localhost で起動します。

2. フロントエンド (Next.js) の構築
リポジトリをクローン

Bash

git clone <フロントエンドのリポジトリURL>
cd <フロントエンドのディレクトリ>
依存パッケージをインストール

Bash

npm install
環境変数の設定

Bash

cp .env.local.example .env.local
.env.localファイルを編集し、Firebase設定とバックエンドAPIのURLを設定

コード スニペット

NEXT_PUBLIC_FIREBASE_API_KEY="..."
NEXT_PUBLIC_FIREBASE_AUTH_DOMAIN="..."
# ... (その他のFirebase設定) ...
NEXT_PUBLIC_API_BASE_URL="http://localhost/api"
開発サーバーを起動

Bash

npm run dev
フロントエンドは http://localhost:3000 で起動します。

▲ 目次に戻る

Firebaseのセットアップ
Firebaseプロジェクトの作成

Firebaseコンソールにアクセスし、新しいプロジェクトを作成します。

ウェブアプリの登録

プロジェクト設定からウェブアプリを登録し、表示されるfirebaseConfigオブジェクトの値をフロントエンドの.env.localにコピーします。

Authenticationの有効化

Authenticationサービスへ移動し、「メール/パスワード」によるサインインを有効にします。

サービスアカウント秘密鍵の取得

プロジェクト設定の「サービスアカウント」タブへ移動し、「新しい秘密鍵の生成」をクリックしてJSONファイルをダウンロードします。このファイルをバックエンドプロジェクト内の安全な場所に配置し、そのコンテナ内パスをバックエンドの.envファイルにあるFIREBASE_CREDENTIALSに設定します。

▲ 目次に戻る

テストの実行
バックエンドテスト (PHPUnit)
バックエンドのAPIが正しく機能するかをテストします。

Bash

# バックエンドのディレクトリで実行
./vendor/bin/sail artisan test
フロントエンドテスト (Jest)
フロントエンドのコンポーネントが正しく表示・動作するかをテストします。

Bash

# フロントエンドのディレクトリで実行
npm test
▲ 目次に戻る

テストアカウント
シーディングを実行すると、以下のテスト用アカウントが作成されます。

メールアドレス: test@example.com

パスワード: password

▲ 目次に戻る

ER図（参考）
本アプリケーションの主要なデータベース構成です。
| テーブル名 | 概要                                |
| :--------- | :---------------------------------- |
| users    | ユーザー情報（FirebaseのUIDを含む） |
| posts    | 投稿内容                            |
| comments | 投稿へのコメント                    |
| likes    | 投稿への「いいね」                  |

▲ 目次に戻る
