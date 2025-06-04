<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

uses(TestCase::class, RefreshDatabase::class);

// Registration Tests
test('users can register with valid data', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'device_name' => 'iPhone 15',
    ];

    $response = $this->postJson(route('v1.auth.register'), $userData);

    $response->assertStatus(201)
        ->assertJsonStructure(['token']);

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
});

test('users cannot register with invalid data', function () {
    // Missing required fields
    $response = $this->postJson(route('v1.auth.register'), []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'password', 'device_name']);

    // Invalid email
    $response = $this->postJson(route('v1.auth.register'), [
        'name' => 'Test User',
        'email' => 'not-an-email',
        'password' => 'password123',
        'device_name' => 'iPhone 15',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);

    // Password too short
    $response = $this->postJson(route('v1.auth.register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'pass', // Less than 8 characters
        'device_name' => 'iPhone 15',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

test('users cannot register with duplicate email', function () {
    // Create a user first
    User::factory()->create([
        'email' => 'existing@example.com',
    ]);

    // Try to register with the same email
    $response = $this->postJson(route('v1.auth.register'), [
        'name' => 'Another User',
        'email' => 'existing@example.com',
        'password' => 'password123',
        'device_name' => 'iPhone 15',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

// Login Tests
test('users can login with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->postJson(route('v1.auth.login'), [
        'email' => 'test@example.com',
        'password' => 'password123',
        'device_name' => 'iPhone 15',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['token']);
});

test('users cannot login with invalid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    // Wrong password
    $response = $this->postJson(route('v1.auth.login'), [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
        'device_name' => 'iPhone 15',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);

    // Wrong email
    $response = $this->postJson(route('v1.auth.login'), [
        'email' => 'wrong@example.com',
        'password' => 'password123',
        'device_name' => 'iPhone 15',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('login validation fails with missing fields', function () {
    $response = $this->postJson(route('v1.auth.login'), []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password', 'device_name']);
});

// User Info Tests
test('authenticated users can retrieve their profile', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson(route('v1.auth.user'));

    $response->assertStatus(200)
        ->assertJson([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
});

test('unauthenticated users cannot access profile', function () {
    $response = $this->getJson(route('v1.auth.user'));

    $response->assertStatus(401);
});

// Logout Tests
test('authenticated users can logout', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson(route('v1.auth.logout'));

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Logged out successfully.'
        ]);
});
