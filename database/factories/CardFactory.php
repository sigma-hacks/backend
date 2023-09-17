<?php

namespace Database\Factories;

use App\Helpers\MainHelper;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();

        if ($user->id > 999999999999) {
            throw new Exception('User id is biggest');
        }

        $userAge = $user->birth_date->age;

        $avialableTariffs = [null];
        if ($userAge >= 6 && $userAge <= 19) {
            $avialableTariffs[] = 1;
        } elseif ($userAge >= 45) {
            $avialableTariffs[] = 2;
        } elseif ($userAge <= 150) {
            $avialableTariffs[] = 3;
        }

        $tariffId = MainHelper::getRandomValue($avialableTariffs);

        return [
            'is_active' => rand(0, 1) === 1,
            'user_id' => $user->id,
            'tariff_id' => $tariffId,
            'tariff_expired_at' => $tariffId ? date('Y-m-d H:i:s', rand(1600376204, 1789592204)) : null,
            'identifier' => '2200'.(100000000000 + $user->id),
            'expired_at' => date('Y-m-d H:i:s', rand(1600376204, 1789592204)),
        ];
    }
}
