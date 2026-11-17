<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProductListingTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * PBI #22 - Menampilkan hanya produk berstatus published pada grid katalog.
     */
    public function test_displays_only_published_products_in_grid(): void
    {
        $category = Category::factory()->create(['name' => 'Tenun Ikat']);

        $publishedProduct = Product::factory()->published()->create([
            'category_id' => $category->id,
            'name' => 'Tenun Ikat Sumba Asli',
            'artisan_name' => 'Mama Marta',
            'price' => 1450000,
        ]);

        $draftProduct = Product::factory()->draft()->create([
            'category_id' => $category->id,
            'name' => 'Produk Draft Rahasia',
            'price' => 500000,
        ]);

        $this->browse(function (Browser $browser) use ($publishedProduct, $draftProduct) {
            $browser->visit('/produk')
                ->assertPresent('@product-listing-header')
                ->assertPresent('@product-grid')
                ->assertPresent('@product-card-' . $publishedProduct->id)
                ->assertSeeIn('@product-name-' . $publishedProduct->id, 'Tenun Ikat Sumba Asli')
                ->assertSeeIn('@product-price-' . $publishedProduct->id, 'Rp 1.450.000')
                ->assertMissing('@product-card-' . $draftProduct->id)
                ->assertDontSee('Produk Draft Rahasia');
        });
    }

    /**
     * PBI #22 - Menampilkan empty state ketika tidak ada produk berstatus published.
     */
    public function test_displays_empty_state_when_no_published_products(): void
    {
        Product::factory()->draft()->create([
            'name' => 'Draft Gerabah',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/produk')
                ->assertPresent('@empty-state')
                ->assertSeeIn('@empty-state', 'Belum Ada Produk Tersedia')
                ->assertMissing('@product-grid')
                ->assertDontSee('Draft Gerabah');
        });
    }

    /**
     * PBI #22 - Menampilkan pagination ketika jumlah produk published melebihi batas per halaman (12).
     */
    public function test_paginates_published_products(): void
    {
        $category = Category::factory()->create();

        $oldestProduct = Product::factory()->published()->create([
            'category_id' => $category->id,
            'name' => 'Produk Halaman Kedua Pertama',
        ]);

        $newerProducts = Product::factory()->count(12)->published()->create([
            'category_id' => $category->id,
        ]);

        $latestProduct = $newerProducts->last();

        $this->browse(function (Browser $browser) use ($oldestProduct, $latestProduct) {
            $browser->visit('/produk')
                ->assertPresent('@product-grid')
                ->assertPresent('@pagination')
                ->assertPresent('@pagination-links')
                ->assertSeeIn('@pagination-info', 'Halaman 1 dari 2')
                ->assertPresent('@product-card-' . $latestProduct->id)
                ->assertMissing('@product-card-' . $oldestProduct->id)
                ->click('@pagination-page-2')
                ->waitForTextIn('@pagination-info', 'Halaman 2 dari 2')
                ->assertPathIs('/produk')
                ->assertQueryStringHas('page', '2')
                ->assertSeeIn('@pagination-info', 'Halaman 2 dari 2')
                ->assertPresent('@product-card-' . $oldestProduct->id)
                ->assertSeeIn('@product-name-' . $oldestProduct->id, 'Produk Halaman Kedua Pertama');
        });
    }
}
