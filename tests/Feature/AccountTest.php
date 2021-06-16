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
}
