<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'payment_method' => $this->faker->randomElement(['カード支払い', 'コンビニ支払い']),
            'shipping_postal_code' => $this->faker->postcode(),
            'shipping_address' => $this->faker->address(),
            'shipping_building' => $this->faker->optional()->secondaryAddress(),
            'status' => '支払い済み',
        ];
    }
}
