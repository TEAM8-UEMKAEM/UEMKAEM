<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LogicException;
use Tests\TestCase;

class AdminMasterDataTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_guest_and_regular_user_cannot_open_admin_pages(): void
    {
        $this->get(route('admin.categories.index'))->assertRedirect('/login');
        $this->get(route('admin.audit-logs.index'))->assertRedirect('/login');

        $user = User::factory()->create();
        $this->actingAs($user)->get(route('admin.categories.index'))->assertForbidden();
        $this->actingAs($user)->post(route('admin.materials.store'), ['name' => 'X'])->assertForbidden();
        $this->actingAs($user)->patch(route('admin.regions.toggle', 1))->assertForbidden();
    }

    public function test_admin_can_open_management_pages(): void
    {
        $a = $this->admin();
        foreach (['categories', 'materials', 'regions'] as $name) {
            $this->actingAs($a)->get(route("admin.$name.index"))->assertOk();
            $this->actingAs($a)->get(route("admin.$name.create"))->assertOk();
        }
        $this->actingAs($a)->get(route('admin.audit-logs.index'))->assertOk();
    }

    public function test_category_create_update_and_audit(): void
    {
        $a = $this->admin();

        $this->actingAs($a)->post(route('admin.categories.store'), ['name' => 'Tenun Uji', 'group' => 'Wastra'])
            ->assertRedirect();
        $cat = Category::where('name', 'Tenun Uji')->firstOrFail();
        $this->assertSame('tenun-uji', $cat->slug);
        $this->assertTrue($cat->is_active);
        $this->assertDatabaseHas('audit_logs', ['auditable_type' => 'Category', 'auditable_id' => $cat->id, 'action' => 'created', 'user_id' => $a->id]);

        $this->actingAs($a)->get(route('admin.categories.show', $cat->id))->assertOk()->assertSee('Tenun Uji');
        $this->actingAs($a)->get(route('admin.categories.edit', $cat->id))->assertOk();

        $this->actingAs($a)->put(route('admin.categories.update', $cat->id), ['name' => 'Tenun Baru'])->assertRedirect();
        $cat->refresh();
        $this->assertSame('Tenun Baru', $cat->name);
        $this->assertSame('tenun-uji', $cat->slug); // slug tidak berubah
        $this->assertDatabaseHas('audit_logs', ['auditable_type' => 'Category', 'auditable_id' => $cat->id, 'action' => 'updated']);
    }

    public function test_duplicate_name_is_rejected(): void
    {
        Category::create(['name' => 'Gerabah', 'slug' => 'gerabah']);
        $this->actingAs($this->admin())->post(route('admin.categories.store'), ['name' => 'Gerabah'])
            ->assertSessionHasErrors('name');
        $this->assertSame(1, Category::where('name', 'Gerabah')->count());
    }

    public function test_material_and_region_can_be_created_and_edited(): void
    {
        $a = $this->admin();

        $this->actingAs($a)->post(route('admin.materials.store'), ['name' => 'Benang Katun', 'unit' => 'kg'])->assertRedirect();
        $this->assertDatabaseHas('materials', ['name' => 'Benang Katun', 'slug' => 'benang-katun', 'is_active' => true]);
        $m = \App\Models\Material::first();
        $this->actingAs($a)->put(route('admin.materials.update', $m->id), ['name' => 'Benang Katun Alam', 'unit' => 'kg'])->assertRedirect();
        $this->assertDatabaseHas('materials', ['id' => $m->id, 'name' => 'Benang Katun Alam']);

        $this->actingAs($a)->post(route('admin.regions.store'), ['name' => 'Sumba Timur', 'province' => 'NTT'])->assertRedirect();
        $this->assertDatabaseHas('regions', ['name' => 'Sumba Timur', 'province' => 'NTT', 'is_active' => true]);
        $r = \App\Models\Region::first();
        $this->actingAs($a)->put(route('admin.regions.update', $r->id), ['name' => 'Sumba Barat', 'province' => 'NTT'])->assertRedirect();
        $this->assertDatabaseHas('regions', ['id' => $r->id, 'name' => 'Sumba Barat']);
    }

    public function test_deactivation_hides_category_publicly_but_keeps_products(): void
    {
        $a = $this->admin();
        $cat = Category::create(['name' => 'Kategori Nonaktif Uji', 'slug' => 'kategori-nonaktif-uji']);
        $product = Product::create([
            'category_id' => $cat->id, 'name' => 'Produk Tetap Ada', 'slug' => 'produk-tetap-ada',
            'artisan_name' => 'Ibu Sari', 'origin' => 'Sumba', 'price' => 100000,
        ]);

        $this->get('/kategori')->assertSee('Kategori Nonaktif Uji');

        $this->actingAs($a)->patch(route('admin.categories.toggle', $cat->id))->assertRedirect();
        $this->assertFalse($cat->fresh()->is_active);
        $this->assertDatabaseHas('audit_logs', ['auditable_id' => $cat->id, 'action' => 'deactivated']);

        // kategori hilang dari halaman publik, produk tetap ada & kategori masih terhubung
        $this->get('/kategori')->assertDontSee('Kategori Nonaktif Uji');
        $this->get('/produk')->assertOk()->assertSee('Produk Tetap Ada');
        $this->assertSame($cat->id, $product->fresh()->category_id);

        // admin tetap bisa melihat & mengaktifkan kembali
        $this->actingAs($a)->get(route('admin.categories.index'))->assertSee('Kategori Nonaktif Uji');
        $this->actingAs($a)->patch(route('admin.categories.toggle', $cat->id));
        $this->assertTrue($cat->fresh()->is_active);
        $this->assertDatabaseHas('audit_logs', ['auditable_id' => $cat->id, 'action' => 'activated']);
    }

    public function test_category_with_products_cannot_be_hard_deleted_route_missing(): void
    {
        $cat = Category::create(['name' => 'X', 'slug' => 'x']);
        $this->actingAs($this->admin())->delete('/admin/categories/'.$cat->id)->assertStatus(405);
        $this->assertNotNull(Category::find($cat->id));
    }

    public function test_audit_log_is_append_only(): void
    {
        $log = AuditLog::create(['user_name' => 'x', 'auditable_type' => 'Category', 'auditable_id' => 1, 'action' => 'created']);

        $this->expectException(LogicException::class);
        $log->update(['action' => 'updated']);
    }

    public function test_public_pages_still_work(): void
    {
        $this->get('/')->assertOk();
        $this->get('/produk')->assertOk();
        $this->get('/kategori')->assertOk();
    }
}
