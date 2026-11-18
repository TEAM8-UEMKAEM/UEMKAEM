<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_regular_user_gets_403(): void
    {
        $this->actingAs(User::factory()->create())->get('/dashboard')->assertForbidden();
    }

    public function test_admin_can_open_dashboard(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/dashboard')->assertOk();
    }

    public function test_public_pages_still_work(): void
    {
        $this->get('/')->assertOk();
        $this->get('/produk')->assertOk();
        $this->get('/kategori')->assertOk();
    }
}
