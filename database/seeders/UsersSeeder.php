<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'email' => 'test@mail.ru',
            'password' => bcrypt('123qwe123'),
            'name' => 'Станислав Стрижков',
            'pin' => bcrypt('1234'),
            'code' => 'stanislav-strizhkov',
            'company_id' => 0
        ]);

//        for ($i = 0; $i <= 30000; $i++) {
//            $itemsCreated = $i * 10;
//            echo "Created {$itemsCreated}\n\r";
//            Card::factory()->count(10)->create();
//        }
    }
}
