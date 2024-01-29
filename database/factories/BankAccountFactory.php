<?php

namespace Database\Factories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankAccount>
 */
class BankAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_name' => $this->faker->word(),
            'branch_name' => $this->faker->word(),
            'branch_number' => '1234',
            'account_number' => '567',

            'is_active' => true,
            'login_credentials' => [],
            'bank_id' => $this->faker->randomElement(Bank::pluck('id')->toArray()),
            'created_at' => now(),
        ];
    }
}
