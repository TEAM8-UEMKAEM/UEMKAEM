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
        $cat = Category::create(['name' => 'Tenun', 'slug' => 'tenun']);
        $mat = Material::create(['name' => 'Benang Katun', 'slug' => 'benang-katun']);
        return [$cat, $mat];
    }

    private function rule(Category $c, Material $m, int $min = 80000, int $max = 120000, bool $active = true): PriceRule
    {
        return PriceRule::create(['category_id' => $c->id, 'material_id' => $m->id, 'min_price' => $min, 'max_price' => $max, 'is_active' => $active]);
    }

    // ---------- Admin: aturan harga ----------

    public function test_non_admin_cannot_manage_rules_or_settings(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get(route('admin.price-rules.index'))->assertForbidden();
        $this->actingAs($user)->post(route('admin.price-rules.store'), [])->assertForbidden();
        $this->actingAs($user)->put(route('admin.settings.update'), ['labor_hourly_rate' => 1000])->assertForbidden();
        $this->get(route('admin.price-rules.index'))->assertRedirect('/login');
    }

    public function test_admin_can_create_edit_and_toggle_rule_with_audit(): void
    {
        [$cat, $mat] = $this->seedBase();
        $a = $this->admin();

        $this->actingAs($a)->get(route('admin.price-rules.create'))->assertOk();
        $this->actingAs($a)->post(route('admin.price-rules.store'), [
            'category_id' => $cat->id, 'material_id' => $mat->id, 'min_price' => 80000, 'max_price' => 120000,
        ])->assertRedirect();

        $rule = PriceRule::firstOrFail();
        $this->assertTrue($rule->is_active);
        $this->assertDatabaseHas('audit_logs', ['auditable_type' => 'PriceRule', 'auditable_id' => $rule->id, 'action' => 'created']);

        $this->actingAs($a)->put(route('admin.price-rules.update', $rule->id), ['min_price' => 90000, 'max_price' => 130000])->assertRedirect();
        $this->assertSame(90000, $rule->fresh()->min_price);
        $this->assertDatabaseHas('audit_logs', ['auditable_id' => $rule->id, 'action' => 'updated']);

        $this->actingAs($a)->patch(route('admin.price-rules.toggle', $rule->id))->assertRedirect();
        $this->assertFalse($rule->fresh()->is_active);
        $this->assertDatabaseHas('audit_logs', ['auditable_id' => $rule->id, 'action' => 'deactivated']);

        $this->actingAs($a)->get(route('admin.price-rules.index'))->assertOk()->assertSee('Tenun');
        $this->actingAs($a)->get(route('admin.audit-logs.index'))->assertOk();
    }

    public function test_invalid_rules_are_rejected(): void
    {
        [$cat, $mat] = $this->seedBase();
        $a = $this->admin();
        $base = ['category_id' => $cat->id, 'material_id' => $mat->id];

        // minimum > maksimum
        $this->actingAs($a)->post(route('admin.price-rules.store'), $base + ['min_price' => 200000, 'max_price' => 100000])
            ->assertSessionHasErrors('max_price');
        // harga negatif
        $this->actingAs($a)->post(route('admin.price-rules.store'), $base + ['min_price' => -1, 'max_price' => 100000])
            ->assertSessionHasErrors('min_price');
        $this->assertSame(0, PriceRule::count());

        // duplikat kombinasi
        $this->rule($cat, $mat);
        $this->actingAs($a)->post(route('admin.price-rules.store'), $base + ['min_price' => 1, 'max_price' => 2])
            ->assertSessionHasErrors('category_id');
        $this->assertSame(1, PriceRule::count());

        // kategori nonaktif tidak bisa dipilih
        $off = Category::create(['name' => 'Off', 'slug' => 'off', 'is_active' => false]);
        $this->actingAs($a)->post(route('admin.price-rules.store'), ['category_id' => $off->id, 'material_id' => $mat->id, 'min_price' => 1, 'max_price' => 2])
            ->assertSessionHasErrors('category_id');
    }

    public function test_update_cannot_change_the_combination(): void
    {
        [$cat, $mat] = $this->seedBase();
        $other = Category::create(['name' => 'Lain', 'slug' => 'lain']);
        $rule = $this->rule($cat, $mat);

        $this->actingAs($this->admin())->put(route('admin.price-rules.update', $rule->id),
            ['category_id' => $other->id, 'min_price' => 1, 'max_price' => 2])->assertRedirect();

        $this->assertSame($cat->id, $rule->fresh()->category_id);
    }

    // ---------- Pengaturan tarif ----------

    public function test_admin_can_set_labor_rate_and_it_is_audited(): void
    {
        $a = $this->admin();
        $this->actingAs($a)->get(route('admin.settings.edit'))->assertOk();

        $this->actingAs($a)->put(route('admin.settings.update'), ['labor_hourly_rate' => 25000])->assertRedirect();
        $this->assertSame('25000', Setting::getValue('labor_hourly_rate'));
        $this->assertDatabaseHas('audit_logs', ['auditable_type' => 'Setting', 'action' => 'created', 'auditable_id' => null]);

        $this->actingAs($a)->put(route('admin.settings.update'), ['labor_hourly_rate' => 30000]);
        $this->assertDatabaseHas('audit_logs', ['auditable_type' => 'Setting', 'action' => 'updated']);

        $this->actingAs($a)->put(route('admin.settings.update'), ['labor_hourly_rate' => 0])->assertSessionHasErrors('labor_hourly_rate');
        $this->actingAs($a)->put(route('admin.settings.update'), ['labor_hourly_rate' => 'abc'])->assertSessionHasErrors('labor_hourly_rate');
        $this->assertSame('30000', Setting::getValue('labor_hourly_rate'));

        $this->actingAs($a)->get(route('admin.audit-logs.index'))->assertOk()->assertSee('Tarif upah per jam');
    }

    // ---------- API ----------

    private function api(Category $c, Material $m, $price)
    {
        return $this->getJson("/api/price-check?category_id={$c->id}&material_id={$m->id}&price={$price}");
    }

    public function test_api_is_public_and_returns_correct_results(): void
    {
        [$cat, $mat] = $this->seedBase();
        $this->rule($cat, $mat, 80000, 120000);

        $this->api($cat, $mat, 100000)->assertOk()
            ->assertJsonPath('data.valid', true)->assertJsonPath('data.status', 'within_range')
            ->assertJsonPath('data.rule.min_price', 80000)->assertJsonPath('data.category.name', 'Tenun');

        $this->api($cat, $mat, 80000)->assertJsonPath('data.status', 'within_range'); // batas dihitung sah
        $this->api($cat, $mat, 120000)->assertJsonPath('data.status', 'within_range');

        $this->api($cat, $mat, 50000)->assertOk()
            ->assertJsonPath('data.valid', false)->assertJsonPath('data.status', 'below_minimum')->assertJsonPath('data.difference', 30000);

        $this->api($cat, $mat, 130000)->assertOk()
            ->assertJsonPath('data.valid', false)->assertJsonPath('data.status', 'above_maximum')->assertJsonPath('data.difference', 10000);
    }

    public function test_api_handles_missing_and_inactive_rules(): void
    {
        [$cat, $mat] = $this->seedBase();
        $this->api($cat, $mat, 1000)->assertOk()->assertJsonPath('data.valid', null)->assertJsonPath('data.status', 'no_rule');

        $this->rule($cat, $mat, 1, 2, false);
        $this->api($cat, $mat, 1000)->assertOk()->assertJsonPath('data.valid', null)->assertJsonPath('data.status', 'rule_inactive');
    }

    public function test_api_validates_input_with_422(): void
    {
        [$cat, $mat] = $this->seedBase();

        $this->getJson('/api/price-check')->assertStatus(422)->assertJsonValidationErrors(['category_id', 'material_id', 'price']);
        $this->api($cat, $mat, -5)->assertStatus(422)->assertJsonValidationErrors('price');
        $this->api($cat, $mat, 'abc')->assertStatus(422)->assertJsonValidationErrors('price');

        $off = Category::create(['name' => 'Off', 'slug' => 'off', 'is_active' => false]);
        $this->api($off, $mat, 1000)->assertStatus(422)->assertJsonValidationErrors('category_id');
    }

    // ---------- Panduan harga ----------

    private function guideUrl(Category $c, Material $m, $cost, $hours): string
    {
        return route('price-guide', ['category_id' => $c->id, 'material_id' => $m->id, 'material_cost' => $cost, 'labor_hours' => $hours]);
    }

    public function test_guide_refuses_to_calculate_without_labor_rate(): void
    {
        [$cat, $mat] = $this->seedBase();
        $this->rule($cat, $mat);

        $this->get(route('price-guide'))->assertOk()->assertSee('Tarif upah per jam belum ditetapkan');
        $this->get($this->guideUrl($cat, $mat, 50000, 2))->assertOk()
            ->assertSee('Tarif upah per jam belum ditetapkan')->assertDontSee('Hasil Perhitungan');
    }

    public function test_guide_calculates_on_server_and_checks_rule(): void
    {
        [$cat, $mat] = $this->seedBase();
        $this->rule($cat, $mat, 80000, 120000);
        Setting::setValue('labor_hourly_rate', '20000');

        // 50.000 + 2 jam x 20.000 = 90.000 -> dalam rentang
        $this->get($this->guideUrl($cat, $mat, 50000, 2))->assertOk()
            ->assertSee('Hasil Perhitungan')->assertSee('Rp 40.000')->assertSee('Rp 90.000')
            ->assertSee('Rp 80.000')->assertSee('Rp 120.000')->assertSee('Sesuai harga adil');

        // 10.000 + 1 jam x 20.000 = 30.000 -> di bawah minimum
        $this->get($this->guideUrl($cat, $mat, 10000, 1))->assertSee('Rp 30.000')->assertSee('Di bawah batas minimum');

        // 100.000 + 2,5 jam x 20.000 = 150.000 -> di atas maksimum
        $this->get($this->guideUrl($cat, $mat, 100000, 2.5))->assertSee('Rp 150.000')->assertSee('Melebihi batas maksimum');
    }

    public function test_guide_without_rule_still_estimates_and_says_so(): void
    {
        [$cat, $mat] = $this->seedBase();
        Setting::setValue('labor_hourly_rate', '20000');

        $this->get($this->guideUrl($cat, $mat, 50000, 2))->assertOk()
            ->assertSee('Rp 90.000')->assertSee('Belum ada aturan harga');
    }

    public function test_guide_validates_input_and_hides_inactive_options(): void
    {
        [$cat, $mat] = $this->seedBase();
        Setting::setValue('labor_hourly_rate', '20000');

        $this->get($this->guideUrl($cat, $mat, 50000, -1))->assertSessionHasErrors('labor_hours');
        $this->get($this->guideUrl($cat, $mat, -5, 1))->assertSessionHasErrors('material_cost');

        Category::create(['name' => 'Kategori Tersembunyi', 'slug' => 'tersembunyi', 'is_active' => false]);
        $this->get(route('price-guide'))->assertOk()->assertSee('Tenun')->assertDontSee('Kategori Tersembunyi');
    }

    public function test_public_pages_still_work(): void
    {
        $this->get('/')->assertOk();
        $this->get('/produk')->assertOk();
        $this->get('/kategori')->assertOk();
    }
}
