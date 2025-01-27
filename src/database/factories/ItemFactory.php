<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word(),
            'brand_name' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(100, 9999999),
            'img_url' => 'images/dummy.jpg',
            'condition' => $this->faker->randomElement(['良好', '目立った傷や汚れなし', 'やや傷や汚れあり', '状態が悪い']),
            'status' => $this->faker->randomElement(['出品中', '売却済み', '出品停止']),
        ];
    }
}
