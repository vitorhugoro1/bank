<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Domains\Users\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;

class UserTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function canCreateUser()
    {
        $payload = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'document' => $this->faker->cpf(false),
            'birthday' => Carbon::parse($this->faker->dateTimeBetween('-60 years', '-18 years'))->format('Y-m-d'),
            'password' => 'password'
        ];

        $this->postJson(route('signin'), $payload)
            ->assertCreated()
            ->assertJsonStructure([
                'token'
            ]);
    }

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
                'access_token',
                'token_type'
            ]);

        ['access_token' => $token] = $response->decodeResponseJson();

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson(route('user.me'))
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
