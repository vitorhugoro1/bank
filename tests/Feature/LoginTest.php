<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function canMakeLogin()
    {
        User::factory()->create([
            'email' => 'example@example.com'
        ]);

        $payload = [
            'email' => 'example@example.com',
            'password' => 'password'
        ];

        $response = $this->postJson(route('login'), $payload);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'token'
            ]);

        ['token' => $token] = $response->decodeResponseJson();

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson(route('me'))
            ->assertOk();
    }

    /** @test */
    public function canValidatedLogin()
    {
        User::factory()->create([
            'email' => 'example@example.com'
        ]);

        $payload = [
            'email' => 'example@example.com',
            'password' => 'wrong@password'
        ];

        $this->postJson(route('login'), $payload)
            ->assertJsonValidationErrors([
                'email'
            ]);
    }
}
