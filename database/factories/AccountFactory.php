<?php

namespace Database\Factories;

use App\Domains\Account\Enums\AccountType;
use App\Domains\Account\Models\Account;
use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(AccountType::toArray()),
            'balance' => $this->faker->randomNumber()
        ];
    }
}
