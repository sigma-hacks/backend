<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TariffsSeeder extends Seeder
{

    static array $tariffs = [
        [
            'created_user_id' => 1,
            'company_id' => 0,
            'name' => 'Школьный тариф',
            'amount' => 15,
            'is_active' => true,
            'conditions' => [
                'age' => ['from' => 6, 'to' => 19]
            ]
        ],
        [
            'created_user_id' => 1,
            'company_id' => 0,
            'name' => 'Пенсионер',
            'amount' => 55,
            'is_active' => true,
            'conditions' => [
                'age' => ['from' => 45, 'to' => 150]
            ]
        ],
        [
            'created_user_id' => 1,
            'company_id' => 0,
            'name' => 'Премиальный проезд',
            'amount' => 25,
            'is_active' => true,
            'conditions' => [
                'age' => ['from' => 0, 'to' => 150]
            ]
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::$tariffs as $key => $tariff) {
            try {
                $tariff['id'] = $key + 1;
                $tariff['conditions'] = json_encode($tariff['conditions']);

                DB::table('card_tariffs')->insert($tariff);
            } catch (Exception $e) {
                // printf($e->getMessage());
            }
        }
    }
}
