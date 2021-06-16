<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Domains\Users\Models\User;
use App\Domains\Account\Models\Account;
use App\Domains\Reports\Models\Report;

class ReportTest extends TestCase
{
    use DatabaseMigrations;

    protected User $user;

    protected Account $account;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->account = Account::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function canSeeOperations()
    {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );

        Report::factory(7)->create([
            'account_id' => $this->account->id
        ]);

        $this->getJson(route('user.accounts.reports.index', [$this->account->id]))
            ->assertOk()
            ->assertJsonCount(8)
            ->assertJsonStructure([
                '*' => [
                    'operation',
                    'account_id',
                    'occurred_at',
                    'amount',
                    'balance',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }
}
