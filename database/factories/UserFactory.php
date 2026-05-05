<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    public function definition(): array
    {
        $faker = FakerFactory::create();

        $fullName = $faker->name();

        return [
            'name' => $fullName,
            'full_name' => $fullName,
            'phone' => $faker->numerify('01########'),
            'email' => $faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'years' => $faker->randomElement(['1SVM SEM1', '1SVM SEM2', '2SVM SEM3', null]),
            'programme' => $faker->randomElement(['IPD', 'ISK', 'MTK 1', null]),
            'profile_pic' => null,
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
