<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i <= 30000; $i++) {
            $itemsCreated = $i * 10;
            echo "Created {$itemsCreated}\n\r";
            Card::factory()->count(10)->create();
        }
    }
}
