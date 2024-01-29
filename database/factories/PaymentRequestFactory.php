<?php

namespace Database\Factories;

use App\Models\Payin;
use App\Models\PaymentRequestStatus;
use App\Models\User;
use App\Models\UserAlertLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentRequest>
 */
class PaymentRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_request_unique_code' => $this->faker->uuid,
            'amount' => rand(1000, 10000),
            'status_id' => PaymentRequestStatus::UNPROCESSED,
            'user_id' => User::factory(),
            'created_at' => now(),
        ];
    }
}
