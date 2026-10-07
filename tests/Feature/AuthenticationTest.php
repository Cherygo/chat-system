<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_credentials_return_a_validation_error_instead_of_crashing(): void
    {
        $this->from(route('login'))->post(route('login.user'), [
            'login' => 'missing-user',
            'password' => 'wrong-password',
        ])->assertRedirect(route('login'))->assertSessionHasErrors('login');

        $this->assertGuest();
    }

    public function test_users_can_log_in_with_username_or_email(): void
    {
        $user = User::factory()->create();

        foreach ([$user->username, $user->email] as $login) {
            $this->post(route('login.user'), [
                'login' => $login,
                'password' => 'password',
            ])->assertRedirect(route('chat.index'));
            $this->assertAuthenticatedAs($user);
            $this->post(route('logout'))->assertRedirect(route('index'));
            $this->assertGuest();
        }
    }

    public function test_login_respects_the_intended_chat(): void
    {
        $user = User::factory()->create();
        $this->withSession(['url.intended' => route('chat.index')])->post(route('login.user'), [
            'login' => $user->username,
            'password' => 'password',
        ])->assertRedirect(route('chat.index'));
    }

    public function test_registration_creates_a_welcome_chat_and_remembers_the_user(): void
    {
        $this->post(route('registration.user'), [
            'username' => 'new-user',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'remember' => 'on',
        ])->assertRedirect(route('chat.index'));

        $user = User::where('username', 'new-user')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->remember_token);
        $this->assertSame(1, $user->chats()->count());
        $this->assertDatabaseHas('messages', ['user_id' => $user->id, 'content' => 'Welcome to the chat, new-user!']);
    }

    public function test_logout_clears_session_data_and_regenerates_the_csrf_token(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->withSession(['private-data' => 'secret', '_token' => 'old-token'])
            ->post(route('logout'))->assertRedirect(route('index'))->assertSessionMissing('private-data');

        $this->assertGuest();
        $this->assertNotSame('old-token', session()->token());
    }

    public function test_registration_rejects_mismatched_passwords_without_creating_a_user(): void
    {
        $this->post(route('registration.user'), [
            'username' => 'new-user',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseCount('users', 0);
        $this->assertGuest();
    }
}
