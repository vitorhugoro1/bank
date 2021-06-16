<?php

namespace Database\Factories;

use App\Domains\Account\Models\Account;
use App\Domains\Reports\Enums\ReportOperationEnum;
use App\Domains\Reports\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Report::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'operation' => $this->faker->randomElement([ReportOperationEnum::deposit(), ReportOperationEnum::withdrawal()]),
            'account_id' => Account::factory(),
            'occurred_at' => $this->faker->dateTimeBetween('now', '+15 days'),
            'amount' => $this->faker->randomNumber(),
            'balance' => $this->faker->randomNumber(),
        ];
    }
}
