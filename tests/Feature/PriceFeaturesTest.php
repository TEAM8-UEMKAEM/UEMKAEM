<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Material;
use App\Models\PriceRule;
use App\Models\Setting;
use App\Models\User;
use App\Services\PriceCheckService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PriceFeaturesTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    private function setup3(int $min = 150000, int $max = 250000, bool $active = true): array
    {
        $c = Category::create(['name' => 'Tenun', 'slug' => 'tenun']);
        $m = Material::create(['name' => 'Katun', 'slug' => 'katun']);

        $r = PriceRule::create([
            'category_id' => $c->id,
            'material_id' => $m->id,
            'min_price' => $min,
            'max_price' => $max,
            'is_active' => $active,
        ]);

        return [$c, $m, $r];
    }

    // ---------- Admin: rules ----------

    public function test_non_admin_cannot_manage_rules_or_settings(): void
    {
        $u = User::factory()->create();

        $this->actingAs($u)
            ->get(route('admin.price-rules.index'))
            ->assertForbidden();

        $this->actingAs($u)
            ->post(route('admin.price-rules.store'), [])
            ->assertForbidden();

        $this->actingAs($u)
            ->put(route('admin.settings.update'), ['labor_hourly_rate' => 10000])
            ->assertForbidden();
    }

    public function test_guest_cannot_access_admin_settings(): void
    {
        $this->get(route('admin.settings.edit'))
            ->assertRedirect('/login');
    }

    public function test_admin_creates_and_edits_rule_with_audit(): void
    {
        $a = $this->admin();

        $c = Category::create(['name' => 'Tenun', 'slug' => 'tenun']);
        $m = Material::create(['name' => 'Katun', 'slug' => 'katun']);

        $this->actingAs($a)->get(route('admin.price-rules.index'))->assertOk();
        $this->actingAs($a)->get(route('admin.price-rules.create'))->assertOk();

        $this->actingAs($a)->post(route('admin.price-rules.store'), [
            'category_id' => $c->id,
            'material_id' => $m->id,
            'min_price' => 100000,
            'max_price' => 300000,
        ])->assertRedirect();

        $rule = PriceRule::firstOrFail();

        $this->assertTrue($rule->is_active);

        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => 'PriceRule',
            'auditable_id' => $rule->id,
            'action' => 'created',
        ]);

        $this->actingAs($a)->put(route('admin.price-rules.update', $rule->id), [
            'min_price' => 120000,
            'max_price' => 300000,
        ])->assertRedirect();

        $this->assertSame(120000, $rule->fresh()->min_price);

        $this->assertDatabaseHas('audit_logs', [
            'auditable_id' => $rule->id,
            'action' => 'updated',
        ]);

        $this->actingAs($a)->get(route('admin.audit-logs.index'))->assertOk();
    }

    public function test_invalid_rules_are_rejected(): void
    {
        $a = $this->admin();

        [$c, $m] = $this->setup3();

        $m2 = Material::create(['name' => 'Sutra', 'slug' => 'sutra']);

        // Negative minimum price
        $this->actingAs($a)->post(route('admin.price-rules.store'), [
            'category_id' => $c->id,
            'material_id' => $m2->id,
            'min_price' => -5,
            'max_price' => 100,
        ])->assertSessionHasErrors('min_price');

        // Minimum price greater than maximum price
        $this->actingAs($a)->post(route('admin.price-rules.store'), [
            'category_id' => $c->id,
            'material_id' => $m2->id,
            'min_price' => 500,
            'max_price' => 100,
        ])->assertSessionHasErrors('max_price');

        // Duplicate category and material combination
        $this->actingAs($a)->post(route('admin.price-rules.store'), [
            'category_id' => $c->id,
            'material_id' => $m->id,
            'min_price' => 1,
            'max_price' => 2,
        ])->assertSessionHasErrors('material_id');

        // Inactive category cannot be selected
        $c->update(['is_active' => false]);

        $this->actingAs($a)->post(route('admin.price-rules.store'), [
            'category_id' => $c->id,
            'material_id' => $m2->id,
            'min_price' => 1,
            'max_price' => 2,
        ])->assertSessionHasErrors('category_id');

        $this->assertSame(1, PriceRule::count());
    }

    public function test_rule_can_be_deactivated_and_reactivated(): void
    {
        [, , $rule] = $this->setup3();

        $a = $this->admin();

        $this->actingAs($a)
            ->patch(route('admin.price-rules.toggle', $rule->id))
            ->assertRedirect();

        $this->assertFalse($rule->fresh()->is_active);

        $this->assertDatabaseHas('audit_logs', [
            'auditable_id' => $rule->id,
            'action' => 'deactivated',
        ]);

        $this->actingAs($a)->get(route('admin.price-rules.index'))->assertOk();

        $this->actingAs($a)->patch(route('admin.price-rules.toggle', $rule->id));

        $this->assertTrue($rule->fresh()->is_active);
    }

    // ---------- Settings ----------

    public function test_admin_sets_labor_rate_and_it_is_audited(): void
    {
        $a = $this->admin();

        $this->actingAs($a)->get(route('admin.settings.edit'))->assertOk();

        $this->actingAs($a)->put(route('admin.settings.update'), [
            'labor_hourly_rate' => 0,
        ])->assertSessionHasErrors('labor_hourly_rate');

        $this->assertNull(Setting::getValue('labor_hourly_rate'));

        $this->actingAs($a)->put(route('admin.settings.update'), [
            'labor_hourly_rate' => 25000,
        ])->assertRedirect();

        $this->assertSame('25000', Setting::getValue('labor_hourly_rate'));

        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => 'Setting',
            'action' => 'created',
        ]);

        $this->actingAs($a)->put(route('admin.settings.update'), [
            'labor_hourly_rate' => 30000,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => 'Setting',
            'action' => 'updated',
        ]);
    }

    // ---------- API ----------

    public function test_api_valid_below_above(): void
    {
        [$c, $m] = $this->setup3();

        $q = fn ($p) => route('api.price-check', [
            'category_id' => $c->id,
            'material_id' => $m->id,
            'price' => $p,
        ]);

        $this->getJson($q(200000))
            ->assertOk()
            ->assertJson([
                'status' => 'valid',
                'valid' => true,
                'rule' => [
                    'min_price' => 150000,
                    'max_price' => 250000,
                ],
            ]);

        $this->getJson($q(150000))
            ->assertOk()
            ->assertJson(['valid' => true]);

        $this->getJson($q(250000))
            ->assertOk()
            ->assertJson(['valid' => true]);

        $this->getJson($q(100000))
            ->assertOk()
            ->assertJson([
                'status' => 'below_minimum',
                'valid' => false,
            ]);

        $this->getJson($q(300000))
            ->assertOk()
            ->assertJson([
                'status' => 'above_maximum',
                'valid' => false,
            ]);
    }

    public function test_api_missing_inactive_rule_and_validation(): void
    {
        [$c, $m, $rule] = $this->setup3();

        $m2 = Material::create(['name' => 'Sutra', 'slug' => 'sutra']);

        $this->getJson(route('api.price-check', [
            'category_id' => $c->id,
            'material_id' => $m2->id,
            'price' => 1000,
        ]))
            ->assertStatus(404)
            ->assertJson([
                'status' => 'no_rule',
                'valid' => null,
            ]);

        $rule->update(['is_active' => false]);

        $this->getJson(route('api.price-check', [
            'category_id' => $c->id,
            'material_id' => $m->id,
            'price' => 200000,
        ]))
            ->assertStatus(404)
            ->assertJson([
                'status' => 'rule_inactive',
                'valid' => null,
                'rule' => null,
            ]);

        $this->getJson(route('api.price-check', [
            'category_id' => $c->id,
            'material_id' => $m->id,
        ]))->assertStatus(422)->assertJsonValidationErrors('price');

        $this->getJson(route('api.price-check', [
            'category_id' => $c->id,
            'material_id' => $m->id,
            'price' => -1,
        ]))->assertStatus(422);

        $this->getJson(route('api.price-check', [
            'category_id' => 9999,
            'material_id' => $m->id,
            'price' => 100,
        ]))->assertStatus(422);
    }

    // ---------- Price guide ----------

    public function test_price_guide_page_is_public(): void
    {
        $this->get('/panduan-harga')
            ->assertOk()
            ->assertSee('Panduan Harga');
    }

    public function test_price_guide_without_labor_rate_does_not_calculate(): void
    {
        [$c, $m] = $this->setup3();

        $this->get('/panduan-harga?' . http_build_query([
            'category_id' => $c->id,
            'material_id' => $m->id,
            'material_cost' => 100000,
            'labor_hours' => 5,
        ]))
            ->assertOk()
            ->assertSee('belum ditetapkan');
    }

    public function test_price_guide_calculates_server_side(): void
    {
        [$c, $m] = $this->setup3(150000, 250000);

        Setting::setValue('labor_hourly_rate', '20000');

        // 100.000 + 5 x 20.000 = 200.000 -> within range
        $res = (new PriceCheckService)->estimate($c->id, $m->id, 100000, 5);

        $this->assertSame(100000, $res['labor_cost']);
        $this->assertSame(200000, $res['estimated_price']);
        $this->assertTrue($res['check']['valid']);

        $this->get('/panduan-harga?' . http_build_query([
            'category_id' => $c->id,
            'material_id' => $m->id,
            'material_cost' => 100000,
            'labor_hours' => 5,
        ]))
            ->assertOk()
            ->assertSee('Rp 200.000')
            ->assertSee('Sesuai aturan harga adil');

        // 50.000 + 1 x 20.000 = 70.000 -> below minimum
        $this->get('/panduan-harga?' . http_build_query([
            'category_id' => $c->id,
            'material_id' => $m->id,
            'material_cost' => 50000,
            'labor_hours' => 1,
        ]))
            ->assertOk()
            ->assertSee('Rp 70.000')
            ->assertSee('Tidak sesuai aturan harga adil');
    }

    public function test_price_guide_rejects_inactive_selection_and_bad_input(): void
    {
        [$c, $m] = $this->setup3();

        Setting::setValue('labor_hourly_rate', '20000');

        $c->update(['is_active' => false]);

        $this->get('/panduan-harga?' . http_build_query([
            'category_id' => $c->id,
            'material_id' => $m->id,
            'material_cost' => 1000,
            'labor_hours' => 1,
        ]))
            ->assertOk()
            ->assertDontSee('Perkiraan harga')
            ->assertSee('Jenis kriya');

        $c->update(['is_active' => true]);

        $this->get('/panduan-harga?' . http_build_query([
            'category_id' => $c->id,
            'material_id' => $m->id,
            'material_cost' => -5,
            'labor_hours' => 0,
        ]))
            ->assertOk()
            ->assertDontSee('Perkiraan harga');
    }
}
