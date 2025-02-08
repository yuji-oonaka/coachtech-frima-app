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
