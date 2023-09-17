<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for( $i = 1; $i <= 3000; $i++ ) {
            $cards = Card::factory(100)->make();
            $chunks = $cards->chunk(50);
            $chunks->each(function ($chunk) {
                Card::insert($chunk->toArray());
            });

            echo "Work with index: {$i}\n\r";
        }
    }
}
