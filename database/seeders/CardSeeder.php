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
        $cards = Card::factory(10000)->make();

        $chunks = $cards->chunk(2000);

        $chunks->each(function ($chunk) {
            Card::insert($chunk->toArray());
        });
    }
}
