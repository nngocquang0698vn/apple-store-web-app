<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Nguyễn Văn A',
            'email' => 'customer@example.com',
            'phone' => '0912345678',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ], $overrides);
    }

    public function test_register_page_renders_successfully(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
        $response->assertSee('Đăng ký tài khoản', false);
        $response->assertSee('name="email"', false);
        $response->assertSee('name="phone"', false);
    }

    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->post(route('register.store'), $this->validPayload());

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Nguyễn Văn A',
            'email' => 'customer@example.com',
            'phone' => '0912345678',
            'role' => UserRole::Customer->value,
            'status' => UserStatus::Active->value,
        ]);

        $user = User::query()->where('email', 'customer@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_register_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'customer@example.com']);

        $response = $this->from(route('register'))
            ->post(route('register.store'), $this->validPayload());

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('users', 1);
    }

    public function test_register_rejects_duplicate_phone(): void
    {
        User::factory()->create(['phone' => '0912345678']);

        $response = $this->from(route('register'))
            ->post(route('register.store'), $this->validPayload([
                'email' => 'another@example.com',
            ]));

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('phone');
        $this->assertDatabaseCount('users', 1);
    }

    public function test_register_rejects_short_password(): void
    {
        $response = $this->from(route('register'))
            ->post(route('register.store'), $this->validPayload([
                'password' => 'short',
                'password_confirmation' => 'short',
            ]));

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('password');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_register_rejects_mismatched_password_confirmation(): void
    {
        $response = $this->from(route('register'))
            ->post(route('register.store'), $this->validPayload([
                'password_confirmation' => 'different-password',
            ]));

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('password');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_register_ignores_role_from_request(): void
    {
        $response = $this->post(route('register.store'), $this->validPayload([
            'role' => UserRole::Admin->value,
        ]));

        $response->assertRedirect(route('home'));

        $user = User::query()->where('email', 'customer@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame(UserRole::Customer, $user->role);
    }

    public function test_authenticated_user_cannot_access_register_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('register'));

        $response->assertRedirect(route('home'));
    }

    public function test_authenticated_user_cannot_submit_register_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('register.store'), $this->validPayload([
            'email' => 'new-user@example.com',
            'phone' => '0987654321',
        ]));

        $response->assertRedirect(route('home'));
        $this->assertDatabaseMissing('users', ['email' => 'new-user@example.com']);
    }
}
