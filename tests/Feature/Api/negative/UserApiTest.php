<?php

use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\Notification;

use function Pest\Laravel\postJson;

beforeEach(function () {
    Mail::fake();
    $this->artisan('migrate:fresh');
});

it('fails to create a user with missing name', function () {
    $response = postJson('/api/users', [
        'email' => 'john.doe@example.com',
        'password' => 'password'
    ]);
    // Debugging line 
    //dd($response->json());

    $response->assertStatus(400)
        ->assertJsonValidationErrors(['name']);
});

it('fails to create a user with invalid email', function () {
    $response = postJson('/api/users', [
        'name' => 'John Doe',
        'email' => 'invalid-email',
        'password' => 'password'
    ]);

    $response->assertStatus(400)
        ->assertJsonValidationErrors(['email']);
});

it('fails to create a user with existing email', function () {
    User::factory()->create(['email' => 'john.doe@example.com']);

    $response = postJson('/api/users', [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 'password'
    ]);

    $response->assertStatus(400)
        ->assertJsonValidationErrors(['email']);
});

it('fails to create a user with short password', function () {
    $response = postJson('/api/users', [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 'short'
    ]);

    $response->assertStatus(400)
        ->assertJsonValidationErrors(['password']);
});
