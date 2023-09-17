<?php

namespace Database\Seeders;

use App\Models\Company;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusRoutesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        try {
            for ($i = 1; $i <= 100; $i++) {
                DB::table('bus_routes')->insert([
                    'id' => $i,
                    'is_active' => true,
                    'user_id' => rand(1, 15),
                    'company_id' => Company::DEFAULT_ID,
                    'price' => rand(5, 80),
                    'price_station' => rand(0, 15) === 1 ? rand(1, 3) : 0,
                    'price_distance' => rand(0, 15) === 1 ? rand(1, 3) : 0,
                ]);
            }
        } catch (Exception $e) {
            // Empty catch
        }

    }
}
