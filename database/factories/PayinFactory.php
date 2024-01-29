<?php

namespace Database\Factories;

use App\Models\BankAccount;
use App\Models\PayinStatus;
use App\Models\PaymentRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payin>
 */
class PayinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'input' => 'test',//$this->faker->text(100),
            'amount' => rand(1000, 10000),
            'remainder' => '',
            'deposit_manage_id' => $this->faker->unique()->numerify(),
            'status_id' => PayinStatus::UNPROCESSED,//$this->faker->randomElement(PayinStatus::pluck('id')->toArray()),
            'bank_account_id' => BankAccount::factory(),
            'created_at' => now(),
        ];
    }
}
