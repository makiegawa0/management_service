<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserAlertLevel;
use Illuminate\Database\Eloquent\Factories\Factory;
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
        return [
            'user_code' => Str::random(),
            'email' => $this->faker->safeEmail(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'payment_code' => User::generatePaymentCode(),
            'alert_level_id' => UserAlertLevel::NORMAL,
            'created_at' => now(),
        ];
    }
}
