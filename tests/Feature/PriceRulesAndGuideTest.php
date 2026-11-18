<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Material;
use App\Models\PriceRule;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PriceRulesAndGuideTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    private function seedBase(): array
    {
        $cat = Category::create([
            'name' => 'Tenun',
            'slug' => 'tenun',
        ]);

        $mat = Material::create([
            'name' => 'Benang Katun',
            'slug' => 'benang-katun',
        ]);

        return [$cat, $mat];
    }

    private function rule(
        Category $c,
        Material $m,
        int $min = 80000,
        int $max = 120000,
        bool $active = true
    ): PriceRule {
        return PriceRule::create([
            'category_id' => $c->id,
            'material_id' => $m->id,
            'min_price' => $min,
            'max_price' => $max,
            'is_active' => $active,
        ]);
    }

    // ---------- Admin: aturan harga ----------

    public function test_non_admin_cannot_manage_rules_or_settings(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.price-rules.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->post(route('admin.price-rules.store'), [])
            ->assertForbidden();

        $this->actingAs($user)
            ->put(route('admin.settings.update'), [
                'labor_hourly_rate' => 1000,
            ])
            ->assertForbidden();
    }

    public function test_guest_cannot_access_admin_pages(): void
    {
        $this->get(route('admin.price-rules.index'))
            ->assertRedirect('/login');

        $this->get(route('admin.settings.edit'))
            ->assertRedirect('/login');
    }

    public function test_admin_can_create_edit_and_toggle_rule_with_audit(): void
    {
        [$cat, $mat] = $this->seedBase();
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('admin.price-rules.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->post(route('admin.price-rules.store'), [
                'category_id' => $cat->id,
                'material_id' => $mat->id,
                'min_price' => 80000,
                'max_price' => 120000,
            ])
            ->assertRedirect();

        $rule = PriceRule::firstOrFail();

        $this->assertTrue($rule->is_active);

        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => 'PriceRule',
            'auditable_id' => $rule->id,
            'action' => 'created',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.price-rules.update', $rule->id), [
                'min_price' => 90000,
                'max_price' => 130000,
            ])
            ->assertRedirect();

        $this->assertSame(90000, $rule->fresh()->min_price);

        $this->assertDatabaseHas('audit_logs', [
            'auditable_id' => $rule->id,
            'action' => 'updated',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.price-rules.toggle', $rule->id))
            ->assertRedirect();

        $this->assertFalse($rule->fresh()->is_active);

        $this->assertDatabaseHas('audit_logs', [
            'auditable_id' => $rule->id,
            'action' => 'deactivated',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.price-rules.index'))
            ->assertOk()
            ->assertSee('Tenun');

        $this->actingAs($admin)
            ->get(route('admin.audit-logs.index'))
            ->assertOk();
    }

    public function test_invalid_rules_are_rejected(): void
    {
        [$cat, $mat] = $this->seedBase();
        $admin = $this->admin();

        $base = [
            'category_id' => $cat->id,
            'material_id' => $mat->id,
        ];

        $this->actingAs($admin)
            ->post(route('admin.price-rules.store'), $base + [
                'min_price' => 200000,
                'max_price' => 100000,
            ])
            ->assertSessionHasErrors('max_price');

        $this->actingAs($admin)
            ->post(route('admin.price-rules.store'), $base + [
                'min_price' => -1,
                'max_price' => 100000,
            ])
            ->assertSessionHasErrors('min_price');

        $this->assertSame(0, PriceRule::count());

        $this->rule($cat, $mat);

        $this->actingAs($admin)
            ->post(route('admin.price-rules.store'), $base + [
                'min_price' => 1,
                'max_price' => 2,
            ])
            ->assertSessionHasErrors('material_id');

        $this->assertSame(1, PriceRule::count());

        $inactiveCategory = Category::create([
            'name' => 'Off',
            'slug' => 'off',
            'is_active' => false,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.price-rules.store'), [
                'category_id' => $inactiveCategory->id,
                'material_id' => $mat->id,
                'min_price' => 1,
                'max_price' => 2,
            ])
            ->assertSessionHasErrors('category_id');
    }

    public function test_update_cannot_change_the_combination(): void
    {
        [$cat, $mat] = $this->seedBase();

        $other = Category::create([
            'name' => 'Lain',
            'slug' => 'lain',
        ]);

        $rule = $this->rule($cat, $mat);

        $this->actingAs($this->admin())
            ->put(route('admin.price-rules.update', $rule->id), [
                'category_id' => $other->id,
                'min_price' => 1,
                'max_price' => 2,
            ])
            ->assertRedirect();

        $this->assertSame($cat->id, $rule->fresh()->category_id);
    }

    // ---------- Pengaturan tarif ----------

    public function test_admin_can_set_labor_rate_and_it_is_audited(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('admin.settings.edit'))
            ->assertOk();

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'labor_hourly_rate' => 25000,
            ])
            ->assertRedirect();

        $this->assertSame(
            '25000',
            Setting::getValue('labor_hourly_rate')
        );

        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => 'Setting',
            'action' => 'created',
            'auditable_id' => null,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'labor_hourly_rate' => 30000,
            ]);

        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => 'Setting',
            'action' => 'updated',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'labor_hourly_rate' => 0,
            ])
            ->assertSessionHasErrors('labor_hourly_rate');

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'labor_hourly_rate' => 'abc',
            ])
            ->assertSessionHasErrors('labor_hourly_rate');

        $this->assertSame(
            '30000',
            Setting::getValue('labor_hourly_rate')
        );

        $this->actingAs($admin)
            ->get(route('admin.audit-logs.index'))
            ->assertOk()
            ->assertSee('Tarif upah per jam');
    }

    // ---------- API ----------

    private function api(Category $c, Material $m, $price)
    {
        return $this->getJson(
            "/api/v1/price-check?category_id={$c->id}&material_id={$m->id}&price={$price}"
        );
    }

    public function test_api_is_public_and_returns_correct_results(): void
    {
        [$cat, $mat] = $this->seedBase();

        $this->rule($cat, $mat, 80000, 120000);

        // Price within the allowed range.
        $this->api($cat, $mat, 100000)
            ->assertOk()
            ->assertJsonPath('valid', true)
            ->assertJsonPath('status', 'valid')
            ->assertJsonPath('rule.min_price', 80000)
            ->assertJsonPath('rule.max_price', 120000);

        // Both boundaries are valid.
        $this->api($cat, $mat, 80000)
            ->assertOk()
            ->assertJsonPath('status', 'valid');

        $this->api($cat, $mat, 120000)
            ->assertOk()
            ->assertJsonPath('status', 'valid');

        // Price below the minimum.
        $this->api($cat, $mat, 50000)
            ->assertOk()
            ->assertJsonPath('valid', false)
            ->assertJsonPath('status', 'below_minimum')
            ->assertJsonFragment([
                'message' => 'Harga Rp 50.000 berada di bawah batas minimum harga adil Rp 80.000 (kurang Rp 30.000).',
            ]);

        // Price above the maximum.
        $this->api($cat, $mat, 130000)
            ->assertOk()
            ->assertJsonPath('valid', false)
            ->assertJsonPath('status', 'above_maximum')
            ->assertJsonFragment([
                'message' => 'Harga Rp 130.000 berada di atas batas maksimum Rp 120.000 (lebih Rp 10.000).',
            ]);
    }

    public function test_api_handles_missing_and_inactive_rules(): void
    {
        [$cat, $mat] = $this->seedBase();

        // No rule exists for this combination.
        $this->api($cat, $mat, 1000)
            ->assertNotFound()
            ->assertJsonPath('status', 'no_rule')
            ->assertJsonPath('valid', null);

        // An inactive rule is not available for price checking.
        $this->rule($cat, $mat, 1, 2, false);

        $this->api($cat, $mat, 1000)
            ->assertNotFound()
            ->assertJsonPath('status', 'rule_inactive')
            ->assertJsonPath('valid', null);
    }

    public function test_api_validates_input_with_422(): void
    {
        [$cat, $mat] = $this->seedBase();

        $this->getJson('/api/v1/price-check')
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'category_id',
                'material_id',
                'price',
            ]);

        $this->api($cat, $mat, -5)
            ->assertStatus(422)
            ->assertJsonValidationErrors('price');

        $this->api($cat, $mat, 'abc')
            ->assertStatus(422)
            ->assertJsonValidationErrors('price');
    }

    // ---------- Panduan harga ----------

    private function guideUrl(
        Category $c,
        Material $m,
        $cost,
        $hours
    ): string {
        return route('price-guide', [
            'category_id' => $c->id,
            'material_id' => $m->id,
            'material_cost' => $cost,
            'labor_hours' => $hours,
        ]);
    }

    public function test_guide_refuses_to_calculate_without_labor_rate(): void
    {
        [$cat, $mat] = $this->seedBase();

        $this->rule($cat, $mat);

        $this->get(route('price-guide'))
            ->assertOk()
            ->assertSee(
                'Hasil akan muncul di sini setelah Anda mengisi data di sebelah kiri.'
            );

        $this->get($this->guideUrl($cat, $mat, 50000, 2))
            ->assertOk()
            ->assertSee('2. Hasil perhitungan')
            ->assertSee(
                'Tarif upah per jam belum ditetapkan oleh admin, sehingga perhitungan belum dapat dilakukan.'
            );
    }

    public function test_guide_calculates_on_server_and_checks_rule(): void
    {
        [$cat, $mat] = $this->seedBase();

        $this->rule($cat, $mat, 80000, 120000);

        Setting::setValue('labor_hourly_rate', '20000');

        // 50,000 material cost + 2 hours x 20,000 = 90,000.
        $this->get($this->guideUrl($cat, $mat, 50000, 2))
            ->assertOk()
            ->assertSee('2. Hasil perhitungan')
            ->assertSee('Rp 40.000')
            ->assertSee('Rp 90.000')
            ->assertSee('Rp 80.000')
            ->assertSee('Rp 120.000')
            ->assertSee('Sesuai aturan harga adil.');

        // 10,000 material cost + 1 hour x 20,000 = 30,000.
        $this->get($this->guideUrl($cat, $mat, 10000, 1))
            ->assertOk()
            ->assertSee('Rp 30.000')
            ->assertSee('Tidak sesuai aturan harga adil.')
            ->assertSee(
                'Harga Rp 30.000 berada di bawah batas minimum harga adil Rp 80.000 (kurang Rp 50.000).'
            );

        // 100,000 material cost + 2.5 hours x 20,000 = 150,000.
        $this->get($this->guideUrl($cat, $mat, 100000, 2.5))
            ->assertOk()
            ->assertSee('Rp 150.000')
            ->assertSee('Tidak sesuai aturan harga adil.')
            ->assertSee(
                'Harga Rp 150.000 berada di atas batas maksimum Rp 120.000 (lebih Rp 30.000).'
            );
    }

    public function test_guide_without_rule_still_estimates_and_says_so(): void
    {
        [$cat, $mat] = $this->seedBase();

        Setting::setValue('labor_hourly_rate', '20000');

        $this->get($this->guideUrl($cat, $mat, 50000, 2))
            ->assertOk()
            ->assertSee('Rp 90.000')
            ->assertSee('Belum ada aturan harga');
    }

    public function test_guide_handles_invalid_input_and_hides_inactive_options(): void
    {
        [$cat, $mat] = $this->seedBase();

        Setting::setValue('labor_hourly_rate', '20000');

        // Invalid input should display validation errors, not a calculation.
        $this->get($this->guideUrl($cat, $mat, -5, 1))
            ->assertOk()
            ->assertSee('2. Hasil perhitungan')
            ->assertSee('The Biaya bahan field must be at least 0.')
            ->assertSee(
                'Hasil akan muncul di sini setelah Anda mengisi data di sebelah kiri.'
            );

        $this->get($this->guideUrl($cat, $mat, 50000, -1))
            ->assertOk()
            ->assertSee('2. Hasil perhitungan')
            ->assertSee('The Jam kerja field must be at least 0.1.');

        Category::create([
            'name' => 'Kategori Tersembunyi',
            'slug' => 'tersembunyi',
            'is_active' => false,
        ]);

        $this->get(route('price-guide'))
            ->assertOk()
            ->assertSee('Tenun')
            ->assertDontSee('Kategori Tersembunyi');
    }

    public function test_public_pages_still_work(): void
    {
        $this->get('/')->assertOk();

        $this->get('/produk')->assertOk();

        $this->get('/kategori')->assertOk();
    }
}
