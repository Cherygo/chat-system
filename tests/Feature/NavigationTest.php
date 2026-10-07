<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_actions_link_to_authentication_routes(): void
    {
        $this->get(route('index'))->assertOk()
            ->assertSee('href="'.route('login').'"', false)
            ->assertSee('href="'.route('registration').'"', false)
            ->assertDontSee('href="#"', false);
    }

    public function test_authenticated_homepage_links_to_chats(): void
    {
        $this->actingAs(User::factory()->create())->get(route('index'))->assertOk()
            ->assertSee('href="'.route('chat.index').'"', false)
            ->assertDontSee('/messages', false);
        $this->get(route('login'))->assertRedirect(route('chat.index'));
    }

    public function test_pages_render_one_complete_html_document_without_dead_password_links(): void
    {
        foreach (['index', 'login', 'registration'] as $route) {
            $response = $this->get(route($route))->assertOk()->assertDontSee('Forgot your password?');
            $this->assertSame(1, substr_count($response->getContent(), '<!DOCTYPE html>'));
            $response->assertSee('</head>', false)->assertSee('</body>', false)->assertSee('</html>', false);
        }
    }
}
