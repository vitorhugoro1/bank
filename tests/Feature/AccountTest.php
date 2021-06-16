<?php

namespace Tests\Feature;

use App\Domains\Account\Models\Account;
use App\Domains\Users\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use DatabaseMigrations;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function canCreateAnAccount()
    {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );

        $this->postJson(route('user.accounts.store'), [
            'type' => 'savings',
            'balance' => 20
        ])
            ->assertCreated()
            ->assertJsonStructure([
                'id',
                'balance',
                'type',
                'user_id',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas(Account::class, [
            'user_id' => $this->user->id,
            'type' => 'savings',
            'balance' => 20
        ]);
    }

    /** @test */
    public function canValidateAccountStore()
    {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );

        $this->postJson(route('user.accounts.store'), [
            'type' => 'wrongtype',
            'balance' => 20
        ])
            ->assertJsonValidationErrors([
                'type'
            ]);
    }

    /** @test */
    public function canSeeUserAccounts()
    {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );

        Account::factory(3)->create([
            'user_id' => $this->user->id
        ]);

        $this->getJson(route('user.accounts.index'))
            ->assertOk()
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'balance',
                    'type',
                    'user_id',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    /** @test */
    public function notCanSeeWithdrawal()
    {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );

        $account = Account::factory()->create([
            'user_id' => User::factory()->create(),
            'balance' => 30
        ]);

        $this->getJson(route('user.accounts.show', [$account->id]))
            ->assertStatus(403);
    }

    /** @test */
    public function canMakeDeposit()
    {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );

        $account = Account::factory()->create([
            'user_id' => $this->user->id,
            'balance' => 30
        ]);

        $payload = [
            'amount' => 20
        ];

        $response = $this->postJson(route('user.accounts.deposit', [$account->id]), $payload)
            ->assertOk()
            ->assertJsonStructure([
                'depositAmount',
                'balance',
            ]);

        $decodedResponse = $response->decodeResponseJson();

        $this->assertEquals(50, $decodedResponse['balance']);

        $this->assertDatabaseHas(Account::class, [
            'id' => $account->id,
            'balance' => 50
        ]);
    }

    /** @test */
    public function canWithdrawalAmount()
    {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );

        $account = Account::factory()->create([
            'user_id' => $this->user->id,
            'balance' => 30
        ]);

        $payload = [
            'amount' => 20
        ];

        $response = $this->postJson(route('user.accounts.withdrawal', [$account->id]), $payload);

        $response->assertOk()
            ->assertJsonStructure([
                'withdrawalAmount',
                'balance',
                'notes',
            ]);

        $decodedResponse = $response->decodeResponseJson();

        $this->assertDatabaseHas(Account::class, [
            'id' => $account->id,
            'balance' => $decodedResponse['balance']
        ]);
    }

    /**
     * @test
     * @dataProvider providerValidateWithdrawal
     */
    public function canValidateWithdrawalOnAccount(
        int $balance,
        int $withdrawalAmount,
        int $statusCode,
        string $message
    ) {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );

        $account = Account::factory()->create([
            'user_id' => $this->user->id,
            'balance' => $balance
        ]);

        $payload = [
            'amount' => $withdrawalAmount
        ];

        $this->postJson(route('user.accounts.withdrawal', [$account->id]), $payload)
            ->assertStatus($statusCode)
            ->assertJsonFragment([
                'message' => $message
            ]);
    }

    public function providerValidateWithdrawal()
    {
        return [
            'Does not have enough balance' => [
                30,
                31,
                400,
                'Account does not have enough balance.'
            ],
            'Does not have note option' => [
                30,
                10,
                400,
                'Not has note option for selected value.'
            ],
            'Cannot withdrawal selected amount' => [
                230,
                230,
                400,
                'Not can withdrawal selected amount.'
            ]
        ];
    }
}
