<?php

use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\Notification;

use function Pest\Laravel\{postJson, getJson};

beforeEach(function () {
    Mail::fake();
    $this->artisan('migrate:fresh');
});

it('creates a new user and sends email', function () {
    $response = postJson('/api/users', [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 'password'
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['id', 'name', 'email', 'created_at']);

    $user = User::where('email', 'john.doe@example.com')->first();
    expect($user)->not->toBeNull();

    Mail::assertSent(Notification::class, 2);
});

it('gets paginated list of users with orders_count', function () {
    User::factory()->count(10)->create()->each(function ($user) {
        $user->orders()->createMany(Order::factory()->count(5)->make()->toArray());
    });

    $response = getJson('/api/users?page=1&sortBy=name');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'page',
            'users' => [
                '*' => ['id', 'name', 'email', 'created_at', 'orders_count']
            ]
        ]);

    $users = $response->json('users');
    expect($users)->toHaveCount(10);
});
