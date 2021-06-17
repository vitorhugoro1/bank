<?php

namespace Tests\Unit;

use App\Domains\Account\Models\Account;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

class LockOperationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('lock.operation')
            ->post('/{account}/test', function () {
                return 'OK';
            });
    }

    /** @test */
    public function canLockOperation()
    {
        $account = Account::factory()->create();

        Cache::put(
            "{$account->id}_operation",
            now()->addSeconds(5)->timestamp,
            5
        );

        $this->postJson("/{$account->id}/test")
            ->assertStatus(409)
            ->assertJsonFragment([
                'message' => 'Account has operation on execution.'
            ]);
    }

    /** @test */
    public function notBlockIfNotLocked()
    {
        $account = Account::factory()->create();

        $this->postJson("/{$account->id}/test")
            ->assertOk();
    }
}
