<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();
        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition' => '新品',
                'status' => '出品中',
                'user_id' => $userIds[array_rand($userIds)],
                'brand_name' => 'エンポリオ・アルマーニ',
                'categories' => ['ファッション', 'メンズ', 'アクセサリー']
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition' => '目立った傷や汚れなし',
                'status' => '出品中',
                'user_id' => $userIds[array_rand($userIds)],
                'categories' => ['家電']
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition' => '傷や汚れあり',
                'status' => '出品中',
                'user_id' => $userIds[array_rand($userIds)],
                'categories' => ['キッチン']
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition' => '全体的に状態が悪い',
                'status' => '出品中',
                'user_id' => $userIds[array_rand($userIds)],
                'categories' => ['ファッション', 'メンズ']
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition' => '新品',
                'status' => '出品中',
                'user_id' => $userIds[array_rand($userIds)],
                'categories' => ['家電']
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition' => '目立った傷や汚れなし',
                'status' => '出品中',
                'user_id' => $userIds[array_rand($userIds)],
                'categories' => ['家電']
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition' => '傷や汚れあり',
                'status' => '出品中',
                'user_id' => $userIds[array_rand($userIds)],
                'categories' => ['ファッション', 'レディース']
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition' => '全体的に状態が悪い',
                'status' => '出品中',
                'user_id' => $userIds[array_rand($userIds)],
                'categories' => ['キッチン']
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition' => '新品',
                'status' => '出品中',
                'user_id' => $userIds[array_rand($userIds)],
                'categories' => ['キッチン']
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition' => '目立った傷や汚れなし',
                'status' => '売却済み',
                'user_id' => $userIds[array_rand($userIds)],
                'categories' => ['コスメ', 'レディース']
            ],
        ];

        foreach ($items as $item) {
            $categories = $item['categories'];
            unset($item['categories']);

            $newItem = Item::create($item);

            // カテゴリーの紐付け
            $categoryIds = Category::whereIn('name', $categories)->pluck('id');
            $newItem->categories()->attach($categoryIds);
        }
    }
}
