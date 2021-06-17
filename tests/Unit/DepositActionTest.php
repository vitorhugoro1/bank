<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Domains\Account\Actions\DepositAction;
use App\Domains\Account\Models\Account;
use App\Domains\Reports\Actions\IssueOperation;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Queue;
use Spatie\QueueableAction\Testing\QueueableActionFake;
use Illuminate\Support\Testing\Fakes\QueueFake;

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
        Queue::fake();

        $account = Account::factory()->create([
            'balance' => $balance
        ]);

        $updatedBalance = $this->service->execute($account, $amount);

        $this->assertEquals($expectedBalance, $updatedBalance);

        QueueableActionFake::assertPushed(IssueOperation::class);

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
