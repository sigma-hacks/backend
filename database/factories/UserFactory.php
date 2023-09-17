<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);
        $name = fake()->lastName($gender).' '.fake()->firstName($gender).' '.fake()->middleName($gender);
        $birth_date = fake()->date('Y-m-d');

        return [
            'role_id' => 0,
            'name' => $name,
            'code' => fake()->numerify(Str::slug($name).'-######'),
            'phone' => '79'.fake()->unique()->numberBetween(000000001, 999099090),
            'email' => fake()->unique()->freeEmail(),
            'password' => Hash::make(fake()->password(8, 32)),
            'birth_date' => $birth_date,
        ];
    }
}
