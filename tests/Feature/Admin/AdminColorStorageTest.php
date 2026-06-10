<?php

namespace Tests\Feature\Admin;

use App\Models\Color;
use App\Models\StorageOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminColorStorageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_colors(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.colors.store'), [
            'name' => 'Desert Titanium',
            'slug' => '',
            'hex_code' => '#C9B8A6',
            'is_active' => '1',
            'sort_order' => '4',
        ]);

        $color = Color::query()->where('slug', 'desert-titanium')->first();

        $response->assertRedirect(route('admin.colors.index'));
        $this->assertNotNull($color);
        $this->assertDatabaseHas('colors', [
            'name' => 'Desert Titanium',
            'hex_code' => '#C9B8A6',
        ]);

        $this->actingAs($admin)->patch(route('admin.colors.update', $color), [
            'name' => 'Titan sa mạc',
            'slug' => 'titan-sa-mac',
            'hex_code' => '#BFA58E',
            'is_active' => '0',
            'sort_order' => '5',
        ])->assertRedirect(route('admin.colors.index'));

        $this->assertDatabaseHas('colors', [
            'id' => $color->id,
            'name' => 'Titan sa mạc',
            'is_active' => false,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.colors.destroy', $color))
            ->assertRedirect(route('admin.colors.index'));

        $this->assertDatabaseMissing('colors', ['id' => $color->id]);
    }

    public function test_admin_can_manage_storage_options(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.storage-options.store'), [
            'label' => '2 TB',
            'capacity_gb' => '2048',
            'is_active' => '1',
            'sort_order' => '2048',
        ]);

        $storage = StorageOption::query()->where('capacity_gb', 2048)->first();

        $response->assertRedirect(route('admin.storage-options.index'));
        $this->assertNotNull($storage);
        $this->assertDatabaseHas('storage_options', [
            'label' => '2 TB',
            'capacity_gb' => 2048,
        ]);

        $this->actingAs($admin)->patch(route('admin.storage-options.update', $storage), [
            'label' => '2048 GB',
            'capacity_gb' => '2048',
            'is_active' => '0',
            'sort_order' => '2048',
        ])->assertRedirect(route('admin.storage-options.index'));

        $this->assertDatabaseHas('storage_options', [
            'id' => $storage->id,
            'label' => '2048 GB',
            'is_active' => false,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.storage-options.destroy', $storage))
            ->assertRedirect(route('admin.storage-options.index'));

        $this->assertDatabaseMissing('storage_options', ['id' => $storage->id]);
    }

    public function test_storage_capacity_must_be_unique(): void
    {
        $admin = User::factory()->admin()->create();
        StorageOption::factory()->create(['capacity_gb' => 256]);

        $response = $this->actingAs($admin)
            ->from(route('admin.storage-options.create'))
            ->post(route('admin.storage-options.store'), [
                'label' => '256 GB',
                'capacity_gb' => '256',
                'is_active' => '1',
                'sort_order' => '256',
            ]);

        $response->assertRedirect(route('admin.storage-options.create'));
        $response->assertSessionHasErrors('capacity_gb');
    }
}
