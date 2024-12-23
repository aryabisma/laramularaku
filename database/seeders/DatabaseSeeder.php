<?php

namespace Database\Seeders;

use App\Models\User; 
use App\Models\Order;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Generate 50 users with random active status and random records of user's order
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory(50)->create()->each(function ($user) {
            $randomize_orders = rand(0, 20);
            if($randomize_orders<>0)
                Order::factory($randomize_orders)->create(['user_id' => $user->id]);
        });
    }
}
