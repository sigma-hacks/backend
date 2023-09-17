<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{

    static array $roles = [
        [
            'is_guest' => true,
            'name' => 'Гость',
            'code' => 'guest'
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

    }
}
