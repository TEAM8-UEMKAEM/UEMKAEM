<?php

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\Material;
use App\Models\PriceRule;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArtisanProductManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropAllTables();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('unit')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('province')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('artisans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('workshop_name')->nullable();
            $table->string('specialty')->nullable();
            $table->text('story')->nullable();
            $table->string('location')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->string('verification_status')->default('verified');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->foreignId('material_id')->nullable()->constrained('materials')->restrictOnDelete();
            $table->foreignId('artisan_id')->nullable()->constrained('artisans')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('artisan_name');
            $table->string('origin');
            $table->unsignedInteger('price');
            $table->unsignedTinyInteger('fair_trade_percent')->default(90);
            $table->unsignedSmallInteger('stock')->default(0);
            $table->string('image')->nullable();
            $table->string('status')->default('published');
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('path');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('price_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('material_id')->constrained('materials')->restrictOnDelete();
            $table->unsignedInteger('min_price');
            $table->unsignedInteger('max_price');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('fair_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->unsignedInteger('min_price');
            $table->unsignedInteger('max_price');
            $table->unsignedInteger('recommended_price')->nullable();
            $table->unsignedInteger('material_cost')->nullable();
            $table->unsignedInteger('labor_cost')->nullable();
            $table->unsignedTinyInteger('artisan_share_percent')->default(90);
            $table->text('notes')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
        });

        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    private function createVerifiedArtisan(string $name = 'Mama Maria'): array
    {
        $user = User::factory()->create();
        $region = Region::create([
            'name' => 'Sumba Timur ' . uniqid(),
            'slug' => 'sumba-timur-' . uniqid(),
            'province' => 'NTT',
            'is_active' => true,
        ]);

        $artisan = Artisan::create([
            'user_id' => $user->id,
            'region_id' => $region->id,
            'name' => $name,
            'slug' => 'artisan-' . uniqid(),
            'workshop_name' => 'Sanggar Tenun Mandiri',
            'location' => 'Waingapu, NTT',
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        return [$user, $artisan, $region];
    }

    private function seedCatalogMaster(): array
    {
        $category = Category::create([
            'name' => 'Tenun Ikat',
            'slug' => 'tenun-ikat',
            'is_active' => true,
        ]);

        $material = Material::create([
            'name' => 'Benang Katun',
            'slug' => 'benang-katun',
            'is_active' => true,
        ]);

        PriceRule::create([
            'category_id' => $category->id,
            'material_id' => $material->id,
            'min_price' => 150000,
            'max_price' => 300000,
            'is_active' => true,
        ]);

        return [$category, $material];
    }

    public function test_guest_and_unverified_users_cannot_access_artisan_portal(): void
    {
        $this->get(route('artisan.products.index'))->assertRedirect('/login');

        $regularUser = User::factory()->create();
        $this->actingAs($regularUser)->get(route('artisan.products.index'))->assertForbidden();

        $unverifiedUser = User::factory()->create();
        Artisan::create([
            'user_id' => $unverifiedUser->id,
            'name' => 'Belum Terverifikasi',
            'slug' => 'unverified-' . uniqid(),
            'is_verified' => false,
            'verification_status' => 'pending',
        ]);

        $this->actingAs($unverifiedUser)->get(route('artisan.products.index'))->assertForbidden();
        $this->actingAs($unverifiedUser)->post(route('artisan.products.store'), [])->assertForbidden();
    }

    public function test_verified_artisan_can_create_product_with_category_and_material(): void
    {
        [$user, $artisan, $region] = $this->createVerifiedArtisan();
        [$category, $material] = $this->seedCatalogMaster();

        $this->actingAs($user)->get(route('artisan.products.create'))->assertOk();

        $response = $this->actingAs($user)->post(route('artisan.products.store'), [
            'name' => 'Kain Tenun Sumba Klasik',
            'description' => 'Ditenun manual menggunakan pewarna alami indigo.',
            'category_id' => $category->id,
            'material_id' => $material->id,
            'region_id' => $region->id,
            'price' => 220000,
            'stock' => 5,
            'fair_trade_percent' => 90,
            'status' => 'draft',
        ]);

        $response->assertRedirect(route('artisan.products.index'));
        $response->assertSessionHas('success');
        $response->assertSessionMissing('warning');

        $this->assertDatabaseHas('products', [
            'artisan_id' => $artisan->id,
            'category_id' => $category->id,
            'material_id' => $material->id,
            'name' => 'Kain Tenun Sumba Klasik',
            'price' => 220000,
            'stock' => 5,
            'status' => 'draft',
        ]);
    }

    public function test_creating_product_rejects_inactive_category_or_material(): void
    {
        [$user] = $this->createVerifiedArtisan();
        $inactiveCategory = Category::create([
            'name' => 'Kategori Nonaktif',
            'slug' => 'kategori-nonaktif',
            'is_active' => false,
        ]);
        $inactiveMaterial = Material::create([
            'name' => 'Material Nonaktif',
            'slug' => 'material-nonaktif',
            'is_active' => false,
        ]);

        $this->actingAs($user)->post(route('artisan.products.store'), [
            'name' => 'Produk Uji',
            'description' => 'Deskripsi produk uji',
            'category_id' => $inactiveCategory->id,
            'material_id' => $inactiveMaterial->id,
            'price' => 200000,
            'stock' => 2,
        ])->assertSessionHasErrors(['category_id', 'material_id']);
    }

    public function test_artisan_can_upload_multiple_product_photos_and_manage_stock(): void
    {
        Storage::fake('public');

        [$user, $artisan, $region] = $this->createVerifiedArtisan();
        [$category, $material] = $this->seedCatalogMaster();

        $tinyPng = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        $photo1 = UploadedFile::fake()->createWithContent('kain-1.png', $tinyPng);
        $photo2 = UploadedFile::fake()->createWithContent('kain-2.png', $tinyPng);

        $this->actingAs($user)->post(route('artisan.products.store'), [
            'name' => 'Selendang Ikat',
            'description' => 'Selendang tenun tangan.',
            'category_id' => $category->id,
            'material_id' => $material->id,
            'region_id' => $region->id,
            'price' => 200000,
            'stock' => 12,
            'photos' => [$photo1, $photo2],
        ])->assertRedirect(route('artisan.products.index'));

        $product = Product::where('name', 'Selendang Ikat')->firstOrFail();
        $this->assertSame(12, $product->stock);
        $this->assertCount(2, $product->images);
        $this->assertNotNull($product->image);

        $firstImage = $product->images->first();
        $secondImage = $product->images->last();
        $this->assertTrue($firstImage->is_primary);
        $this->assertSame($firstImage->path, $product->image);

        // Update stock, switch primary image, and remove first image
        $this->actingAs($user)->put(route('artisan.products.update', $product->id), [
            'name' => 'Selendang Ikat',
            'description' => 'Selendang tenun tangan.',
            'category_id' => $category->id,
            'material_id' => $material->id,
            'region_id' => $region->id,
            'price' => 200000,
            'stock' => 7,
            'primary_image_id' => $secondImage->id,
            'remove_images' => [$firstImage->id],
        ])->assertRedirect(route('artisan.products.edit', $product->id));

        $product->refresh();
        $this->assertSame(7, $product->stock);
        $this->assertSame(1, ProductImage::where('product_id', $product->id)->count());
        $this->assertSame($secondImage->path, $product->image);
    }

    public function test_artisan_receives_fair_price_warning_when_outside_range(): void
    {
        [$user, $artisan, $region] = $this->createVerifiedArtisan();
        [$category, $material] = $this->seedCatalogMaster(); // min 150000, max 300000

        // Below minimum
        $resLow = $this->actingAs($user)->post(route('artisan.products.store'), [
            'name' => 'Kain Harga Rendah',
            'description' => 'Produk di bawah batas minimum.',
            'category_id' => $category->id,
            'material_id' => $material->id,
            'region_id' => $region->id,
            'price' => 100000,
            'stock' => 3,
        ]);

        $resLow->assertRedirect(route('artisan.products.index'));
        $resLow->assertSessionHas('warning');
        $this->assertDatabaseHas('products', ['name' => 'Kain Harga Rendah', 'price' => 100000]);

        // Above maximum
        $product = Product::where('name', 'Kain Harga Rendah')->firstOrFail();
        $resHigh = $this->actingAs($user)->put(route('artisan.products.update', $product->id), [
            'name' => 'Kain Harga Tinggi',
            'description' => 'Produk di atas batas maksimum.',
            'category_id' => $category->id,
            'material_id' => $material->id,
            'region_id' => $region->id,
            'price' => 500000,
            'stock' => 3,
        ]);

        $resHigh->assertRedirect(route('artisan.products.edit', $product->id));
        $resHigh->assertSessionHas('warning');
        $this->assertDatabaseHas('products', ['id' => $product->id, 'price' => 500000]);
    }

    public function test_artisan_can_edit_publish_unpublish_and_archive_own_products(): void
    {
        [$user, $artisan, $region] = $this->createVerifiedArtisan();
        [$otherUser] = $this->createVerifiedArtisan('Perajin Lain');
        [$category, $material] = $this->seedCatalogMaster();

        $product = Product::create([
            'artisan_id' => $artisan->id,
            'artisan_name' => $artisan->name,
            'category_id' => $category->id,
            'material_id' => $material->id,
            'region_id' => $region->id,
            'name' => 'Tas Anyaman',
            'slug' => 'tas-anyaman',
            'description' => 'Tas anyaman tradisional.',
            'origin' => $region->name,
            'price' => 200000,
            'stock' => 4,
            'status' => 'draft',
        ]);

        // Draft is hidden from buyer detail
        $this->get('/products/' . $product->slug)->assertNotFound();

        // Publish -> visible in buyer detail
        $this->actingAs($user)->patch(route('artisan.products.publish', $product->id))->assertRedirect();
        $this->assertSame('published', $product->fresh()->status);
        $this->get('/products/' . $product->slug)->assertOk();

        // Unpublish -> back to draft, hidden from buyer detail
        $this->actingAs($user)->patch(route('artisan.products.unpublish', $product->id))->assertRedirect();
        $this->assertSame('draft', $product->fresh()->status);
        $this->get('/products/' . $product->slug)->assertNotFound();

        // Archive -> archived, hidden from buyer detail
        $this->actingAs($user)->patch(route('artisan.products.archive', $product->id))->assertRedirect();
        $this->assertSame('archived', $product->fresh()->status);
        $this->get('/products/' . $product->slug)->assertNotFound();

        // Other artisan cannot modify or change status
        $this->actingAs($otherUser)->get(route('artisan.products.edit', $product->id))->assertForbidden();
        $this->actingAs($otherUser)->patch(route('artisan.products.publish', $product->id))->assertForbidden();
        $this->actingAs($otherUser)->patch(route('artisan.products.unpublish', $product->id))->assertForbidden();
        $this->actingAs($otherUser)->patch(route('artisan.products.archive', $product->id))->assertForbidden();
    }
}
