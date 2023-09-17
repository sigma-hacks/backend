<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesSeeder extends Seeder
{
    public static array $companies = [
        [
            'is_active' => true,
            'name' => 'Автотранспорт',
            'code' => 'buses',
            'photo' => '',
            'description' => 'Автотранспортные компании объединенные в единую сеть',
        ],
        [
            'is_active' => true,
            'name' => 'Деловые линии',
            'code' => 'bussines-lines',
            'photo' => 'https://static.insales-cdn.com/files/1/7913/14933737/original/%D0%94%D0%B5%D0%BB%D0%BE%D0%B2%D1%8B%D0%B5_%D0%BB%D0%B8%D0%BD%D0%B8%D0%B8_0c75131f275f249e506ea398b3515c92.jpg',
            'description' => 'Услуги населению',
        ],
        [
            'is_active' => true,
            'name' => 'Снежная деревня',
            'code' => 'snow-village',
            'photo' => 'https://snowderevnya.ru/assets/img/logo-sneg.png',
            'description' => 'Развлечения и досуг',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::$companies as $key => $company) {
            try {
                $company['id'] = $key;
                DB::table('companies')->insert($company);
            } catch (Exception $e) {
                // empty catch =(
            }
        }
    }
}
