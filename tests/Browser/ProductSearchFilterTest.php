<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Product;
use App\Models\Region;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProductSearchFilterTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * PBI #23 - Pencarian produk berdasarkan kata kunci dan tetap hanya menampilkan produk published.
     */
    public function test_can_search_published_products_by_keyword(): void
    {
        $matchingPublished = Product::factory()->published()->create([
            'name' => 'Tenun Ikat Sumba Pewarna Akar',
        ]);

        $nonMatchingPublished = Product::factory()->published()->create([
            'name' => 'Gerabah Liat Bakar Kasongan',
        ]);

        $matchingDraft = Product::factory()->draft()->create([
            'name' => 'Tenun Ikat Sumba Draft Internal',
        ]);

        $this->browse(function (Browser $browser) use ($matchingPublished, $nonMatchingPublished, $matchingDraft) {
            $browser->visit('/produk')
                ->type('@search-input', 'Sumba')
                ->click('@filter-submit')
                ->waitForTextIn('@showing-count', 'Menampilkan 1 dari 1')
                ->assertQueryStringHas('q', 'Sumba')
                ->assertPresent('@product-card-' . $matchingPublished->id)
                ->assertSeeIn('@product-name-' . $matchingPublished->id, 'Tenun Ikat Sumba Pewarna Akar')
                ->assertMissing('@product-card-' . $nonMatchingPublished->id)
                ->assertMissing('@product-card-' . $matchingDraft->id)
                ->assertDontSee('Tenun Ikat Sumba Draft Internal');
        });
    }

    /**
     * PBI #23 - Kombinasi filter berdasarkan kategori, rentang harga (min & max), dan wilayah (region).
     */
    public function test_can_filter_by_category_price_range_and_region(): void
    {
        $catTenun = Category::factory()->create(['name' => 'Tenun Tradisi', 'slug' => 'tenun-tradisi']);
        $catBatik = Category::factory()->create(['name' => 'Batik Canting', 'slug' => 'batik-canting']);

        $regSumba = Region::factory()->create(['name' => 'Sumba Timur', 'slug' => 'sumba-timur']);
        $regJogja = Region::factory()->create(['name' => 'DI Yogyakarta', 'slug' => 'di-yogyakarta']);

        $targetProduct = Product::factory()->published()->create([
            'category_id' => $catTenun->id,
            'region_id' => $regSumba->id,
            'name' => 'Tenun Sumba Target Utama',
            'price' => 500000,
        ]);

        $wrongCategoryProduct = Product::factory()->published()->create([
            'category_id' => $catBatik->id,
            'region_id' => $regSumba->id,
            'name' => 'Batik Sumba Beda Kategori',
            'price' => 500000,
        ]);

        $wrongRegionProduct = Product::factory()->published()->create([
            'category_id' => $catTenun->id,
            'region_id' => $regJogja->id,
            'name' => 'Tenun Jogja Beda Wilayah',
            'price' => 500000,
        ]);

        $tooCheapProduct = Product::factory()->published()->create([
            'category_id' => $catTenun->id,
            'region_id' => $regSumba->id,
            'name' => 'Tenun Sumba Harga Murah',
            'price' => 150000,
        ]);

        $tooExpensiveProduct = Product::factory()->published()->create([
            'category_id' => $catTenun->id,
            'region_id' => $regSumba->id,
            'name' => 'Tenun Sumba Harga Mahal',
            'price' => 1500000,
        ]);

        $this->browse(function (Browser $browser) use (
            $catTenun,
            $regSumba,
            $targetProduct,
            $wrongCategoryProduct,
            $wrongRegionProduct,
            $tooCheapProduct,
            $tooExpensiveProduct
        ) {
            $browser->visit('/produk')
                ->select('@filter-category', $catTenun->slug)
                ->select('@filter-region', $regSumba->slug)
                ->type('@filter-min-price', '300000')
                ->type('@filter-max-price', '800000')
                ->click('@filter-submit')
                ->waitForTextIn('@showing-count', 'Menampilkan 1 dari 1')
                ->assertPresent('@product-card-' . $targetProduct->id)
                ->assertSeeIn('@product-name-' . $targetProduct->id, 'Tenun Sumba Target Utama')
                ->assertMissing('@product-card-' . $wrongCategoryProduct->id)
                ->assertMissing('@product-card-' . $wrongRegionProduct->id)
                ->assertMissing('@product-card-' . $tooCheapProduct->id)
                ->assertMissing('@product-card-' . $tooExpensiveProduct->id);
        });
    }

    /**
     * PBI #23 - Pengurutan produk berdasarkan terbaru, harga terendah, dan harga tertinggi.
     */
    public function test_can_sort_products_by_latest_lowest_price_and_highest_price(): void
    {
        $cheapOld = Product::factory()->published()->create([
            'name' => 'Kriya Termurah',
            'price' => 180000,
            'created_at' => now()->subDays(3),
        ]);

        $midProduct = Product::factory()->published()->create([
            'name' => 'Kriya Menengah',
            'price' => 550000,
            'created_at' => now()->subDays(2),
        ]);

        $expensiveNew = Product::factory()->published()->create([
            'name' => 'Kriya Termahal Terbaru',
            'price' => 1350000,
            'created_at' => now()->subDay(),
        ]);

        $this->browse(function (Browser $browser) use ($cheapOld, $midProduct, $expensiveNew) {
            // Default: terbaru (expensiveNew di urutan pertama)
            $browser->visit('/produk')
                ->assertSeeIn('@product-grid article:first-child h3', $expensiveNew->name);

            // Sort harga terendah
            $browser->select('@sort-select', 'price_asc')
                ->click('@filter-submit')
                ->waitForTextIn('@product-grid article:first-child h3', $cheapOld->name)
                ->assertQueryStringHas('sort', 'price_asc')
                ->assertSeeIn('@product-grid article:first-child h3', $cheapOld->name);

            // Sort harga tertinggi
            $browser->select('@sort-select', 'price_desc')
                ->click('@filter-submit')
                ->waitForTextIn('@product-grid article:first-child h3', $expensiveNew->name)
                ->assertQueryStringHas('sort', 'price_desc')
                ->assertSeeIn('@product-grid article:first-child h3', $expensiveNew->name);
        });
    }

    /**
     * PBI #23 - Menampilkan empty state ketika pencarian atau filter tidak menemukan produk.
     */
    public function test_displays_empty_state_when_search_returns_no_results(): void
    {
        Product::factory()->published()->create([
            'name' => 'Ukiran Kayu Jati Jepara',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/produk')
                ->type('@search-input', 'ProdukTidakDitemukanXYZ')
                ->click('@filter-submit')
                ->waitFor('@empty-state')
                ->assertPresent('@empty-state')
                ->assertSeeIn('@empty-state', 'Produk tidak ditemukan')
                ->assertMissing('@product-grid');
        });
    }

    /**
     * PBI #23 - Parameter filter bertahan saat pindah halaman pagination dan reset ke halaman 1 saat filter diubah.
     */
    public function test_persists_filters_on_pagination_and_resets_page_when_filter_changes(): void
    {
        $catTenun = Category::factory()->create(['name' => 'Tenun Nusantara', 'slug' => 'tenun-nusantara']);
        $catGerabah = Category::factory()->create(['name' => 'Gerabah Nusantara', 'slug' => 'gerabah-nusantara']);

        Product::factory()->count(14)->published()->create([
            'category_id' => $catTenun->id,
        ]);

        $gerabahProduct = Product::factory()->published()->create([
            'category_id' => $catGerabah->id,
            'name' => 'Gerabah Eksklusif Halaman Satu',
        ]);

        $this->browse(function (Browser $browser) use ($catTenun, $catGerabah, $gerabahProduct) {
            $browser->visit('/produk')
                ->select('@filter-category', $catTenun->slug)
                ->click('@filter-submit')
                ->waitForTextIn('@showing-count', 'Menampilkan 12 dari 14')
                ->assertQueryStringHas('category', $catTenun->slug)
                ->assertSeeIn('@pagination-info', 'Halaman 1 dari 2')
                ->click('@pagination-page-2')
                ->waitForTextIn('@pagination-info', 'Halaman 2 dari 2')
                ->assertQueryStringHas('category', $catTenun->slug)
                ->assertQueryStringHas('page', '2')
                ->assertSeeIn('@pagination-info', 'Halaman 2 dari 2')
                // Mengubah filter saat berada di halaman 2 harus otomatis kembali ke halaman 1
                ->select('@filter-category', $catGerabah->slug)
                ->click('@filter-submit')
                ->waitForTextIn('@pagination-info', 'Halaman 1 dari 1')
                ->assertQueryStringHas('category', $catGerabah->slug)
                ->assertQueryStringMissing('page')
                ->assertSeeIn('@pagination-info', 'Halaman 1 dari 1')
                ->assertPresent('@product-card-' . $gerabahProduct->id);
        });
    }
}
