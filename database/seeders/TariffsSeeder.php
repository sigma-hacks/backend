<?php

namespace Database\Seeders;

use App\Models\Company;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TariffsSeeder extends Seeder
{
    public static array $tariffs = [
        [
            'created_user_id' => 1,
            'company_id' => Company::DEFAULT_ID,
            'name' => 'Обучающийся',
            'amount' => 15,
            'is_active' => true,
            'conditions' => [
                'age' => ['from' => 6, 'to' => 23],
            ],
        ],
        [
            'created_user_id' => 1,
            'company_id' => Company::DEFAULT_ID,
            'name' => 'Пенсионер',
            'amount' => 55,
            'is_active' => true,
            'conditions' => [
                'age' => ['from' => 45, 'to' => 150],
            ],
        ],
        [
            'created_user_id' => 1,
            'company_id' => Company::DEFAULT_ID,
            'name' => 'Премиальный проезд',
            'amount' => 25,
            'is_active' => true,
            'conditions' => [
                'age' => ['from' => 0, 'to' => 150],
            ],
        ],
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
