<?php

namespace Tests\Browser;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\FairPrice;
use App\Models\Product;
use App\Models\Region;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProductDetailTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * PBI #24 - Navigasi klik kartu produk dari katalog (/produk) menuju halaman detail produk.
     */
    public function test_can_navigate_from_catalog_to_product_detail_page(): void
    {
        $category = Category::factory()->create(['name' => 'Tenun Ikat Tradisi']);
        $region = Region::factory()->create(['name' => 'Sumba Timur, NTT']);
        $artisan = Artisan::factory()->create([
            'region_id' => $region->id,
            'name' => 'Mama Marta',
            'workshop_name' => 'Sanggar Tenun Prailiu',
            'story' => 'Menenun kain ikat bermotif kuda sejak usia remaja menggunakan akar mengkudu.',
            'is_verified' => true,
        ]);

        $product = Product::factory()->published()->create([
            'category_id' => $category->id,
            'region_id' => $region->id,
            'artisan_id' => $artisan->id,
            'name' => 'Kain Tenun Ikat Sumba Pewarna Akar',
            'slug' => 'kain-tenun-ikat-sumba-pewarna-akar',
            'price' => 1450000,
            'stock' => 4,
            'description' => 'Kain tenun ikat asli Sumba Timur dengan pewarna alami akar mengkudu dan daun nila.',
        ]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/produk')
                ->assertPresent('@product-card-' . $product->id)
                ->click('@product-detail-link-' . $product->id)
                ->waitFor('@product-detail')
                ->assertPathIs('/produk/' . $product->slug)
                ->assertSeeIn('@product-title', 'Kain Tenun Ikat Sumba Pewarna Akar');
        });
    }

    /**
     * PBI #24 - Menampilkan detail produk, kisah pengrajin, badge verifikasi, dan rentang fair-price dengan benar.
     */
    public function test_displays_product_detail_artisan_story_verified_badge_and_fair_price(): void
    {
        $category = Category::factory()->create(['name' => 'Batik Tulis Canting']);
        $region = Region::factory()->create(['name' => 'DI Yogyakarta']);
        $artisan = Artisan::factory()->create([
            'region_id' => $region->id,
            'name' => 'Ibu Wiyarni',
            'workshop_name' => 'Sanggar Batik Imogiri',
            'story' => 'Tiga dekade melestarikan batik tulis canting halus dengan malam lebah murni dan pewarna indigofera.',
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        $product = Product::factory()->published()->create([
            'category_id' => $category->id,
            'region_id' => $region->id,
            'artisan_id' => $artisan->id,
            'name' => 'Jarit Batik Tulis Indigofera Klasik',
            'slug' => 'jarit-batik-tulis-indigofera-klasik',
            'price' => 980000,
            'fair_trade_percent' => 92,
            'stock' => 5,
            'description' => 'Batik tulis klasik motif parang seling dengan celupan indigo alami sebanyak 12 kali.',
        ]);

        FairPrice::factory()->create([
            'product_id' => $product->id,
            'category_id' => $category->id,
            'region_id' => $region->id,
            'min_price' => 850000,
            'max_price' => 1150000,
            'recommended_price' => 980000,
            'artisan_share_percent' => 92,
            'notes' => 'Terverifikasi oleh dewan kurator fair-trade Yogyakarta.',
        ]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/produk/' . $product->slug)
                ->assertPresent('@product-detail')
                ->assertSeeIn('@product-title', 'Jarit Batik Tulis Indigofera Klasik')
                ->assertSeeIn('@product-price', 'Rp 980.000')
                ->assertSeeIn('@product-stock', 'Stok Tersedia: 5 Pcs')
                ->assertSeeIn('@product-category', 'Batik Tulis Canting')
                ->assertSeeIn('@product-origin', 'DI Yogyakarta')
                ->assertSeeIn('@product-description', 'Batik tulis klasik motif parang seling')
                // Blok Pengrajin & Verified Badge
                ->assertPresent('@artisan-section')
                ->assertSeeIn('@artisan-name', 'Ibu Wiyarni')
                ->assertSeeIn('@artisan-workshop', 'Sanggar Batik Imogiri')
                ->assertSeeIn('@artisan-region', 'DI Yogyakarta')
                ->assertPresent('@artisan-verified-badge')
                ->assertSeeIn('@artisan-verified-badge', 'Pengrajin Terverifikasi')
                ->assertSeeIn('@artisan-story', 'Tiga dekade melestarikan batik tulis canting halus')
                // Blok Fair-Price
                ->assertPresent('@fair-price-section')
                ->assertPresent('@fair-price-range')
                ->assertSeeIn('@fair-price-min', 'Rp 850.000')
                ->assertSeeIn('@fair-price-max', 'Rp 1.150.000')
                ->assertSeeIn('@fair-price-recommended', 'Rp 980.000')
                ->assertSeeIn('@fair-trade-percent', '92% Hak Perajin')
                ->assertPresent('@fair-price-indicator')
                // Tombol Aksi
                ->assertPresent('@wishlist-toggle')
                ->assertPresent('@add-to-cart-btn');
        });
    }

    /**
     * PBI #24 - Tidak menampilkan badge verifikasi jika status pengrajin belum terverifikasi.
     */
    public function test_hides_verified_badge_when_artisan_is_not_verified(): void
    {
        $unverifiedArtisan = Artisan::factory()->unverified()->create([
            'name' => 'Pak Hendra',
            'story' => 'Pengrajin ukiran kayu baru dalam tahap kurasi.',
        ]);

        $product = Product::factory()->published()->create([
            'artisan_id' => $unverifiedArtisan->id,
            'name' => 'Ukiran Kayu Baru',
            'slug' => 'ukiran-kayu-baru',
        ]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/produk/' . $product->slug)
                ->assertPresent('@product-detail')
                ->assertPresent('@artisan-section')
                ->assertSeeIn('@artisan-name', 'Pak Hendra')
                ->assertMissing('@artisan-verified-badge');
        });
    }

    /**
     * PBI #24 - Akses langsung ke URL produk berstatus draft mengembalikan halaman 404.
     */
    public function test_returns_404_when_accessing_draft_product(): void
    {
        $draftProduct = Product::factory()->draft()->create([
            'name' => 'Produk Kriya Draft Rahasia',
            'slug' => 'produk-kriya-draft-rahasia',
        ]);

        $this->browse(function (Browser $browser) use ($draftProduct) {
            $browser->visit('/produk/' . $draftProduct->slug)
                ->assertMissing('@product-detail')
                ->assertDontSee('Produk Kriya Draft Rahasia')
                ->assertSee('404');
        });
    }
}
