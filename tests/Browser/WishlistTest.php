<?php

namespace Tests\Browser;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WishlistTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * PBI #25 - Guest user yang mengakses /wishlist atau menekan tombol wishlist diarahkan ke halaman login.
     */
    public function test_guest_is_redirected_to_login_when_accessing_or_toggling_wishlist(): void
    {
        $product = Product::factory()->published()->create([
            'name' => 'Tenun Ikat Sumba Guest Test',
            'slug' => 'tenun-ikat-sumba-guest-test',
        ]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->logout()
                ->visit('/wishlist')
                ->waitFor('@login-page')
                ->assertPathIs('/login')
                ->assertSee('Masuk ke Akun UEMKAEM');

            $browser->visit('/produk/' . $product->slug)
                ->assertPresent('@wishlist-toggle')
                ->click('@wishlist-toggle')
                ->waitFor('@login-page')
                ->assertPathIs('/login');
        });
    }

    /**
     * PBI #25 - Buyer yang login dapat menyimpan produk ke wishlist dari katalog (/produk) maupun halaman detail (/produk/{slug}).
     */
    public function test_authenticated_buyer_can_save_to_wishlist_from_catalog_and_detail_page(): void
    {
        $buyer = User::factory()->create();

        $catalogProduct = Product::factory()->published()->create([
            'name' => 'Kriya Katalog Favorit',
            'slug' => 'kriya-katalog-favorit',
        ]);

        $detailProduct = Product::factory()->published()->create([
            'name' => 'Kriya Detail Favorit',
            'slug' => 'kriya-detail-favorit',
        ]);

        $this->browse(function (Browser $browser) use ($buyer, $catalogProduct, $detailProduct) {
            // Simpan dari halaman katalog /produk
            $browser->loginAs($buyer)
                ->visit('/produk')
                ->assertSeeIn('@wishlist-toggle-' . $catalogProduct->id, 'Simpan Wishlist')
                ->click('@wishlist-toggle-' . $catalogProduct->id)
                ->waitForTextIn('@wishlist-toggle-' . $catalogProduct->id, 'Tersimpan di Wishlist')
                ->assertSeeIn('@wishlist-toggle-' . $catalogProduct->id, 'Tersimpan di Wishlist');

            // Simpan dari halaman detail /produk/{slug}
            $browser->visit('/produk/' . $detailProduct->slug)
                ->assertSeeIn('@wishlist-toggle', 'Simpan ke Wishlist')
                ->click('@wishlist-toggle')
                ->waitForTextIn('@wishlist-toggle', 'Tersimpan di Wishlist')
                ->assertSeeIn('@wishlist-toggle', 'Tersimpan di Wishlist');
        });
    }

    /**
     * PBI #25 - Buyer dapat melihat produk di /wishlist, menghapusnya, dan melihat empty state setelah kosong.
     */
    public function test_buyer_can_view_wishlist_remove_item_and_see_empty_state(): void
    {
        $buyer = User::factory()->create();

        $product = Product::factory()->published()->create([
            'name' => 'Gerabah Kasongan Wishlist',
            'slug' => 'gerabah-kasongan-wishlist',
            'price' => 320000,
        ]);

        Wishlist::factory()->create([
            'user_id' => $buyer->id,
            'product_id' => $product->id,
        ]);

        $this->browse(function (Browser $browser) use ($buyer, $product) {
            $browser->loginAs($buyer)
                ->visit('/produk')
                ->click('@nav-wishlist')
                ->waitFor('@wishlist-grid')
                ->assertPathIs('/wishlist')
                ->assertPresent('@wishlist-item-' . $product->id)
                ->assertSeeIn('@wishlist-product-name-' . $product->id, 'Gerabah Kasongan Wishlist')
                ->assertSeeIn('@wishlist-product-price-' . $product->id, 'Rp 320.000')
                ->click('@wishlist-remove-' . $product->id)
                ->waitFor('@wishlist-empty')
                ->assertPresent('@wishlist-empty')
                ->assertSeeIn('@wishlist-empty', 'Wishlist Anda Masih Kosong')
                ->assertPresent('@explore-catalog-btn');
        });
    }

    /**
     * PBI #25 - Produk yang di-unpublish setelah masuk wishlist tetap muncul di /wishlist dengan label "Produk Tidak Tersedia".
     */
    public function test_unpublished_product_in_wishlist_displays_unavailable_badge(): void
    {
        $buyer = User::factory()->create();

        $product = Product::factory()->published()->create([
            'name' => 'Ukiran Kayu Diarsipkan Pengrajin',
            'slug' => 'ukiran-kayu-diarsipkan-pengrajin',
        ]);

        Wishlist::factory()->create([
            'user_id' => $buyer->id,
            'product_id' => $product->id,
        ]);

        // Simulasikan pengrajin mengubah status produk menjadi draft setelah disimpan buyer
        $product->update(['status' => 'draft']);

        $this->browse(function (Browser $browser) use ($buyer, $product) {
            $browser->loginAs($buyer)
                ->visit('/wishlist')
                ->waitFor('@wishlist-grid')
                ->assertPresent('@wishlist-item-' . $product->id)
                ->assertSeeIn('@wishlist-product-name-' . $product->id, 'Ukiran Kayu Diarsipkan Pengrajin')
                ->assertPresent('@product-unavailable-badge')
                ->assertSeeIn('@product-unavailable-badge', 'Produk Tidak Tersedia')
                ->assertPresent('@wishlist-buy-disabled-' . $product->id)
                ->assertDisabled('@wishlist-buy-disabled-' . $product->id);
        });
    }
}
