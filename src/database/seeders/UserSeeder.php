<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Address;
use Faker\Factory;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create('ja_JP');

        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => "User{$i}",
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            Address::create([
                'user_id' => $user->id,
                'postal_code' => substr_replace($faker->postcode, '-', 3, 0),
                'address' => $faker->prefecture . $faker->city . $faker->streetAddress,
                'building' => $faker->optional()->secondaryAddress,
            ]);
        }
    }
}
