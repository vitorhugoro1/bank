<?php

namespace Tests\Unit;

use App\Domains\Account\Models\Account;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Domains\Account\Actions\WithdrawalAction;
use App\Domains\Account\DataTransferObject\WithdrawalRequestData;
use App\Domains\Account\Exceptions\BalanceAccountIsLowException;
use App\Domains\Account\Exceptions\NotCanWithdrawalSelectedAmountException;
use App\Domains\Account\Exceptions\NotHasNoteOptionException;
use Illuminate\Support\Facades\Queue;
use Spatie\QueueableAction\Testing\QueueableActionFake;
use App\Domains\Reports\Actions\IssueOperation;

class WithdrawalActionTest extends TestCase
{
    use DatabaseMigrations;

    private WithdrawalAction $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(WithdrawalAction::class);
    }

    /**
     * @dataProvider provideAcceptedWithdrawalValues
     *
     * @test
     */
    public function canWithdrawalBills(
        int $balanceAmount,
        int $withdrawalAmount,
        array $noteExpected,
        int $notesCountExpected,
        int $balanceAmountExpected
    ) {
        Queue::fake();

        $account = Account::factory()->create([
            'balance' => $balanceAmount
        ]);

        $withdrawalRequestData = new WithdrawalRequestData(
            amount: $withdrawalAmount
        );

        $withdrawalResponse = $this->service->execute($account, $withdrawalRequestData);

        QueueableActionFake::assertPushed(IssueOperation::class);

        $account->refresh();

        $this->assertEquals($withdrawalAmount, $withdrawalResponse->withdrawalAmount);
        $this->assertEquals($balanceAmountExpected, $withdrawalResponse->balance);
        $this->assertCount($notesCountExpected, $withdrawalResponse->notes);
        $this->assertEquals($noteExpected, $withdrawalResponse->notes);
        $this->assertEquals($account->balance, $balanceAmountExpected);
    }

    public function provideAcceptedWithdrawalValues()
    {
        return [
            'from 150 balance, withdrawal 100 with 1 note, then final balance 50' => [
                150,
                100,
                [WithdrawalAction::ONE_HUNDRED_NOTE],
                1,
                50,
            ],
            'from 275 balance, withdrawal 240 with 4 notes, then final balance 35' => [
                275,
                240,
                [WithdrawalAction::ONE_HUNDRED_NOTE, WithdrawalAction::ONE_HUNDRED_NOTE, WithdrawalAction::TWENTY_NOTE,  WithdrawalAction::TWENTY_NOTE],
                4,
                35,
            ]
        ];
    }

    /** @test */
    public function notHasNoteOptionsForAmount()
    {
        Queue::fake();

        $account = Account::factory()->create([
            'balance' => 230
        ]);

        $withdrawalRequestData = new WithdrawalRequestData(
            amount: 230
        );

        $this->expectException(NotCanWithdrawalSelectedAmountException::class);
        $this->expectExceptionMessage('Not can withdrawal selected amount.');

        $this->service->execute($account, $withdrawalRequestData);

        QueueableActionFake::assertPushed(IssueOperation::class);
    }

    /** @test */
    public function notHaveBalanceOnAccount()
    {
        Queue::fake();

        $account = Account::factory()->create([
            'balance' => 50
        ]);

        $withdrawalRequestData = new WithdrawalRequestData(
            amount: 100
        );

        $this->expectException(BalanceAccountIsLowException::class);
        $this->expectExceptionMessage('Account does not have enough balance.');

        $this->service->execute($account, $withdrawalRequestData);

        QueueableActionFake::assertPushed(IssueOperation::class);
    }

    /** @test */
    public function notCanWithdrawalLessThanMinimumNoteAccepted()
    {
        Queue::fake();

        $account = Account::factory()->create([
            'balance' => 100
        ]);

        $withdrawalRequestData = new WithdrawalRequestData(
            amount: 15
        );

        $this->expectException(NotHasNoteOptionException::class);
        $this->expectExceptionMessage('Not has note option for selected value.');

        $this->service->execute($account, $withdrawalRequestData);
        QueueableActionFake::assertPushed(IssueOperation::class);
    }
}
