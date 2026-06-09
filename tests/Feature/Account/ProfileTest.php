<?php

namespace Tests\Feature\Account;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_page(): void
    {
        $response = $this->get(route('account.profile.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('account.profile.edit'));

        $response->assertOk();
        $response->assertSee('Hồ sơ tài khoản', false);
        $response->assertSee($user->email, false);
        $response->assertSee($user->name, false);
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Nguyễn Văn A',
            'phone' => '0912345678',
            'default_address' => null,
        ]);

        $response = $this->actingAs($user)->patch(route('account.profile.update'), [
            'name' => 'Trần Thị B',
            'phone' => '0987654321',
            'default_address' => '123 Đường ABC, Quận 1, TP.HCM',
        ]);

        $response->assertRedirect(route('account.profile.edit'));
        $response->assertSessionHas('success');

        $user->refresh();

        $this->assertSame('Trần Thị B', $user->name);
        $this->assertSame('0987654321', $user->phone);
        $this->assertSame('123 Đường ABC, Quận 1, TP.HCM', $user->default_address);
    }

    public function test_profile_update_rejects_duplicate_phone(): void
    {
        $user = User::factory()->create(['phone' => '0912345678']);
        User::factory()->create(['phone' => '0987654321']);

        $response = $this->from(route('account.profile.edit'))
            ->actingAs($user)
            ->patch(route('account.profile.update'), [
                'name' => $user->name,
                'phone' => '0987654321',
                'default_address' => null,
            ]);

        $response->assertRedirect(route('account.profile.edit'));
        $response->assertSessionHasErrors('phone');

        $user->refresh();
        $this->assertSame('0912345678', $user->phone);
    }

    public function test_user_can_keep_own_phone_when_updating_profile(): void
    {
        $user = User::factory()->create([
            'phone' => '0912345678',
            'name' => 'Nguyễn Văn A',
        ]);

        $response = $this->actingAs($user)->patch(route('account.profile.update'), [
            'name' => 'Nguyễn Văn C',
            'phone' => '0912345678',
            'default_address' => 'Địa chỉ mới',
        ]);

        $response->assertRedirect(route('account.profile.edit'));
        $response->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertSame('Nguyễn Văn C', $user->name);
        $this->assertSame('0912345678', $user->phone);
    }

    public function test_guest_cannot_update_profile(): void
    {
        $response = $this->patch(route('account.profile.update'), [
            'name' => 'Hacker',
            'phone' => '0911111111',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_change_password_with_valid_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->actingAs($user)->patch(route('account.password.update'), [
            'current_password' => 'old-password',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
        ]);

        $response->assertRedirect(route('account.profile.edit'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('new-password123', $user->password));
    }

    public function test_password_update_rejects_wrong_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->from(route('account.profile.edit'))
            ->actingAs($user)
            ->patch(route('account.password.update'), [
                'current_password' => 'wrong-password',
                'password' => 'new-password123',
                'password_confirmation' => 'new-password123',
            ]);

        $response->assertRedirect(route('account.profile.edit'));
        $response->assertSessionHasErrors('current_password');

        $user->refresh();
        $this->assertTrue(Hash::check('old-password', $user->password));
    }

    public function test_password_update_rejects_mismatched_confirmation(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->from(route('account.profile.edit'))
            ->actingAs($user)
            ->patch(route('account.password.update'), [
                'current_password' => 'old-password',
                'password' => 'new-password123',
                'password_confirmation' => 'different-password',
            ]);

        $response->assertRedirect(route('account.profile.edit'));
        $response->assertSessionHasErrors('password');

        $user->refresh();
        $this->assertTrue(Hash::check('old-password', $user->password));
    }

    public function test_guest_cannot_change_password(): void
    {
        $response = $this->patch(route('account.password.update'), [
            'current_password' => 'old-password',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_user_cannot_update_another_users_profile(): void
    {
        $user = User::factory()->create(['name' => 'Nguyễn Văn A']);
        $otherUser = User::factory()->create(['name' => 'Trần Thị B']);

        $this->actingAs($user)->patch(route('account.profile.update'), [
            'name' => 'Hacker',
            'phone' => '0911111111',
            'default_address' => null,
        ]);

        $otherUser->refresh();
        $this->assertSame('Trần Thị B', $otherUser->name);
    }
}
