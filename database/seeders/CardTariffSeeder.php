<?php

namespace Database\Seeders;

use App\Models\CardTariff;
use App\Models\Company;
use Illuminate\Database\Seeder;

class CardTariffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CardTariff::factory()->create([
            'name' => 'Обучающийся',
            'amount' => 15,
            'company' => Company::DEFAULT_ID,
            'conditions' => [
                'age' => [
                    'from' => 6,
                    'to' => 23,
                ],
            ],
        ]);

        CardTariff::factory()->create([
            'name' => 'Пенсионер',
            'amount' => 55,
            'company' => Company::DEFAULT_ID,
            'conditions' => [
                'age' => [
                    'from' => 45,
                    'to' => 150,
                ],
            ],
        ]);

        CardTariff::factory()->create([
            'name' => 'Премиальный проезд',
            'amount' => 25,
            'company' => Company::DEFAULT_ID,
            'conditions' => [
                'age' => [
                    'from' => 0,
                    'to' => 150,
                ],
            ],
        ]);
    }
}
