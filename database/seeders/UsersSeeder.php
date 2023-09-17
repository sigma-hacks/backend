<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for( $i = 0; $i <= 400; $i++ ) {
            echo "Work with index: {$i}\n\r";
            User::factory()->count(10)->create();
        }
    }
}
