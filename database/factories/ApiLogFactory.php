<?php

namespace Database\Factories;

use App\Models\ApiLogStatus;
use App\Models\PaymentRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApiLog>
 */
class ApiLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'request' => "{}",
            'response' => "{'ok': true}",
            'status_id' => $this->faker->randomElement(ApiLogStatus::pluck('id')->toArray()),
            'payment_request_id' => PaymentRequest::factory(),
            'created_at' => now(),
        ];
    }
}
