<?php

namespace Tests\Feature;

use App\Events\ChatEvent;
use App\Events\MessageEvent;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    private function chatWith(User ...$users): Chat
    {
        $chat = Chat::create(['is_group' => count($users) > 2, 'name' => 'Test chat']);
        $chat->users()->attach(array_map(fn ($user) => $user->id, $users));

        return $chat;
    }

    public function test_search_is_case_insensitive_excludes_self_and_validates_input(): void
    {
        $me = User::factory()->create(['username' => 'Alice']);
        $other = User::factory()->create(['username' => 'Alice&Bob']);
        $this->actingAs($me)->getJson(route('chat.search', ['query' => 'ALICE']))
            ->assertOk()->assertExactJson([['id' => $other->id, 'username' => $other->username]]);
        $this->getJson(route('chat.search'))->assertOk()->assertExactJson([]);
        $this->getJson(route('chat.search', ['query' => ['bad']]))->assertUnprocessable()->assertJsonValidationErrors('query');
    }

    public function test_starting_a_direct_chat_twice_reuses_it_and_notifies_the_other_user_once(): void
    {
        Event::fake([ChatEvent::class]);
        [$me, $other] = User::factory()->count(2)->create()->all();
        $this->actingAs($me)->post(route('chat.start', $other))->assertRedirect();
        $chat = Chat::firstOrFail();
        $this->post(route('chat.start', $other))->assertRedirect(route('chat.show', $chat));
        $this->actingAs($other)->post(route('chat.start', $me))->assertRedirect(route('chat.show', $chat));
        $this->assertDatabaseCount('chats', 1);
        $this->assertSame(2, $chat->users()->count());
        Event::assertDispatchedTimes(ChatEvent::class, 1);
    }

    public function test_starting_a_chat_with_yourself_is_rejected(): void
    {
        $me = User::factory()->create();
        $this->actingAs($me)->post(route('chat.start', $me))->assertUnprocessable();
        $this->assertDatabaseCount('chats', 0);
    }

    public function test_duplicate_or_self_group_members_are_rejected_without_orphaned_chats(): void
    {
        [$me, $other] = User::factory()->count(2)->create()->all();
        $this->actingAs($me)->post(route('chat.group'), [
            'name' => 'Team', 'user_ids' => [$other->id, $other->id],
        ])->assertSessionHasErrors('user_ids.0');
        $this->post(route('chat.group'), ['name' => 'Team', 'user_ids' => [$me->id]])->assertSessionHasErrors('user_ids.0');
        $this->assertDatabaseCount('chats', 0);
    }

    public function test_group_creation_includes_the_creator_and_dispatches_an_invitation(): void
    {
        Event::fake([ChatEvent::class]);
        [$me, $other] = User::factory()->count(2)->create()->all();
        $this->actingAs($me)->post(route('chat.group'), ['name' => 'Team', 'user_ids' => [$other->id]])->assertRedirect();
        $chat = Chat::firstOrFail();
        $this->assertTrue($chat->is_group);
        $this->assertEqualsCanonicalizing([$me->id, $other->id], $chat->users->modelKeys());
        Event::assertDispatched(ChatEvent::class, fn ($event) => $event->creatorId === $me->id);
    }

    public function test_invalid_group_input_renders_validation_errors_without_crashing(): void
    {
        $me = User::factory()->create();
        $this->actingAs($me)->from(route('chat.index'))->post(route('chat.group'), [
            'name' => ['invalid'], 'user_ids' => 'invalid',
        ])->assertRedirect(route('chat.index'))->assertSessionHasErrors(['name', 'user_ids']);
        $this->withCookie(session()->getName(), session()->getId())->get(route('chat.index'))
            ->assertOk()->assertSee('data-has-errors="true"', false);
    }

    public function test_nonmembers_cannot_read_or_send_messages(): void
    {
        [$me, $other, $outsider] = User::factory()->count(3)->create()->all();
        $chat = $this->chatWith($me, $other);
        $this->actingAs($outsider)->get(route('chat.show', $chat))->assertForbidden();
        $this->post(route('chat.store', $chat), ['content' => 'Unauthorized'])->assertForbidden();
        $this->assertDatabaseCount('messages', 0);
    }

    public function test_members_can_send_messages_and_content_is_escaped(): void
    {
        Event::fake([MessageEvent::class]);
        [$me, $other] = User::factory()->count(2)->create()->all();
        $chat = $this->chatWith($me, $other);
        $content = '<img src=x onerror=alert(1)>';
        $this->actingAs($me)->post(route('chat.store', $chat), ['content' => $content])->assertRedirect(route('chat.show', $chat));
        $message = $chat->messages()->firstOrFail();
        $this->get(route('chat.show', $chat))->assertOk()->assertSee(e($content), false)
            ->assertSee('data-message-id="'.$message->id.'" class="flex justify-end"', false);
        Event::assertDispatched(MessageEvent::class);
        $this->post(route('chat.store', $chat), ['content' => '   '])->assertSessionHasErrors('content');
        $this->post(route('chat.store', $chat), ['content' => str_repeat('a', 10001)])->assertSessionHasErrors('content');
        $this->assertDatabaseCount('messages', 1);
    }

    public function test_invitation_channels_survive_queue_serialization_without_auth_context(): void
    {
        [$me, $other] = User::factory()->count(2)->create()->all();
        $chat = $this->chatWith($me, $other);
        $event = unserialize(serialize(new ChatEvent($chat, $me->id)));
        $this->assertInstanceOf(ShouldBroadcast::class, $event);
        $this->assertSame('private-user.'.$other->id, $event->broadcastOn()[0]->name);
        $this->assertSame('chat.created', $event->broadcastAs());
        $this->assertArrayNotHasKey('email', $event->broadcastWith()['chat']['users'][0]);
    }

    public function test_message_payload_contains_only_public_sender_fields(): void
    {
        [$me, $other] = User::factory()->count(2)->create()->all();
        $chat = $this->chatWith($me, $other);
        $message = $chat->messages()->create(['user_id' => $me->id, 'content' => 'Hello']);
        $event = new MessageEvent($message);
        $this->assertSame('private-chat.'.$chat->id, $event->broadcastOn()[0]->name);
        $this->assertSame('message.sent', $event->broadcastAs());
        $this->assertSame(['id' => $me->id, 'username' => $me->username], $event->broadcastWith()['message']['sender']);
    }

    public function test_broadcast_subscriptions_require_membership_and_user_ownership(): void
    {
        config(['broadcasting.default' => 'reverb', 'broadcasting.connections.reverb.key' => 'test-key',
            'broadcasting.connections.reverb.secret' => 'test-secret', 'broadcasting.connections.reverb.app_id' => 'test-app']);
        // The test suite boots with the null broadcaster; register channels on the test Reverb driver.
        require base_path('routes/channels.php');
        [$me, $other, $outsider] = User::factory()->count(3)->create()->all();
        $chat = $this->chatWith($me, $other);
        $this->actingAs($me)->postJson('/broadcasting/auth', [
            'socket_id' => '1.2', 'channel_name' => 'private-chat.'.$chat->id,
        ])->assertOk();
        $this->actingAs($outsider)->postJson('/broadcasting/auth', [
            'socket_id' => '1.2', 'channel_name' => 'private-chat.'.$chat->id,
        ])->assertForbidden();
        $this->postJson('/broadcasting/auth', ['socket_id' => '1.2', 'channel_name' => 'private-user.'.$me->id])->assertForbidden();
        $this->postJson('/broadcasting/auth', ['socket_id' => '1.2', 'channel_name' => 'private-user.'.$outsider->id])->assertOk();
    }
}
