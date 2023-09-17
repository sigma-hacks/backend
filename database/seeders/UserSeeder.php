<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'test@mail.ru',
            'password' => bcrypt('123qwe123'),
            'name' => 'Станислав Стрижков',
            'pin' => bcrypt('1234'),
            'code' => 'stanislav-strizhkov',
            'company_id' => Company::DEFAULT_ID
        ]);
    }
}
