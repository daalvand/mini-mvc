<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user(): void
    {
        // Create a new user
        $user = User::create([
             'first_name' => 'John',
             'last_name'  => 'Doe',
             'email'      => 'john@example.com',
             'password'   => 'password123',
        ]);

        // Assert that the user was created
        $this->assertDatabaseHas('users', [
             'id'          => $user->id,
             'first_name'  => 'John',
             'last_name'   => 'Doe',
             'email'       => 'john@example.com',
             'password'    => 'password123',
        ]);
    }

    public function test_update_user(): void
    {
        // Create a new user
        $user = User::create([
             'first_name' => 'John',
             'last_name'  => 'Doe',
             'email'      => 'john@example.com',
             'password'   => 'password123',
        ]);

        // Update the user
        $user->update([
             'first_name' => 'Jane',
             'last_name'  => 'Doe',
             'email'      => 'jane@example.com',
             'password'   => 'newpassword',
        ]);

        // Assert that the user was updated
        $this->assertDatabaseHas('users', [
             'id'          => $user->id,
             'first_name'  => 'Jane',
             'last_name'   => 'Doe',
             'email'       => 'jane@example.com',
             'password'    => 'newpassword',
        ]);
    }

    public function test_find_user(): void
    {
        // Create a new user
        $user = User::create([
             'first_name' => 'John',
             'last_name'  => 'Doe',
             'email'      => 'john@example.com',
             'password'   => 'password123',
        ]);

        // Find the user
        $foundUser = User::findOne($user->id);

        // Assert that the found user matches the created user
        $this->assertEquals($user->id, $foundUser->id);
        $this->assertEquals($user->first_name, $foundUser->first_name);
        $this->assertEquals($user->last_name, $foundUser->last_name);
        $this->assertEquals($user->email, $foundUser->email);
        $this->assertEquals($user->password, $foundUser->password);
    }

    public function test_delete_a_user(): void
    {
        $user = User::create([
             'first_name' => 'John',
             'last_name'  => 'Doe',
             'email'      => 'john@example.com',
             'password'   => 'password123',
        ]);

        User::query()->where('id', $user->id)->delete();

        $this->assertDatabaseMissing('users', [
             'first_name'  => 'John',
             'last_name'   => 'Doe',
             'email'       => 'john@example.com',
             'password'    => 'password123',
        ]);
    }
}

