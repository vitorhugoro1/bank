<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Domains\Account\Actions\DepositAction;
use App\Domains\Account\Models\Account;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DepositActionTest extends TestCase
{
    use DatabaseMigrations;

    private DepositAction $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(DepositAction::class);
    }

    /**
     * @test
     * @dataProvider provideDepositValues
     */
    public function canMakeDeposit(
        int $balance,
        int $amount,
        int $expectedBalance
    ) {
        $account = Account::factory()->create([
            'balance' => $balance
        ]);

        $updatedBalance = $this->service->execute($account, $amount);

        $this->assertEquals($expectedBalance, $updatedBalance);

        $this->assertDatabaseHas(Account::class, [
            'id' => $account->id,
            'balance' => $updatedBalance
        ]);
    }

    public function provideDepositValues()
    {
        return [
            [30, 50, 80],
            [237, 782, 1019],
            [300, 242, 542]
        ];
    }
}
