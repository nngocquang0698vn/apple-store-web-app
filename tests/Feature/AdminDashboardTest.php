<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    public function test_admin_dashboard_renders_successfully(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard', false);
        $response->assertSee('Trạng thái dự án', false);
    }
}
