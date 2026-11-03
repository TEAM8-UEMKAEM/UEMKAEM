<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['Tenun & Wastra Tradisi', 'Tenun & Songket Tradisional', 'Kain tenun ikat dengan motif filosofis leluhur.'],
            ['Batik Tulis Canting', 'Batik Warisan Leluhur', 'Batik tulis dengan malam murni dan pewarna alami.'],
            ['Gerabah & Keramik Tanah', 'Gerabah & Keramik Tradisi', 'Lempung dibakar dengan teknik turun-temurun.'],
            ['Ukiran & Pahat Kayu', 'Ukiran & Pahat Kayu', 'Karya pahat bersertifikat kayu legal FSC.'],
            ['Anyaman Rotan & Serat', 'Anyaman Rotan & Bambu', 'Anyaman serat alami tahan lama.'],
        ];

        foreach ($categories as [$name, $tagline, $description]) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'tagline' => $tagline,
                'description' => $description,
            ]);
        }

        // [kategori(1-5), nama produk, artisan, asal, harga, % fair-trade, stok]
        $products = [
            [1, 'Kain Tenun Ikat Sumba Pewarna Akar', 'Mama Marta', 'Sumba Timur, NTT', 1450000, 90, 1],
            [2, 'Jarit Batik Tulis Indigofera Klasik', 'Ibu Wiyarni', 'Gimpan, DI Yogyakarta', 980000, 92, 5],
            [3, 'Teko Gerabah Liat Bakar Kasongan', 'Pak Slamet', 'Kasongan, Bantul', 280000, 85, 8],
            [5, 'Nampan Anyaman Rotan Motif Dayak', 'Kelompok Ibu Anyam', 'Kutai Barat, Kalbar', 340000, 88, 4],
            [4, 'Patung Ukir Kayu Sonokeling Garuda', 'Ki Wayan', 'Gianyar, Bali', 850000, 91, 1],
            [3, 'Cangkir Keramik Stoneware Abu Sekam', 'Argan Studio', 'Klaten, Jawa Tengah', 175000, 90, 12],
            [1, 'Selendang Songket Pandai Sikek', 'Uni Rona', 'Pandai Sikek, Sumbar', 1200000, 93, 2],
            [5, 'Wadah Serbaguna Daun Lontar Rote', 'Mama Martha', 'Pulau Rote, NTT', 210000, 89, 6],
            [3, 'Piring Saji Terakota Bakar Bayat', 'Mbah Marto', 'Bayat, Klaten', 190000, 87, 10],
            [2, 'Kain Jarit Batik Tulis Sogan Parang', 'Kayu Dewi', 'Surakarta, Jawa Tengah', 1150000, 94, 3],
            [4, 'Tas Noken Rajut Tangan Serat Kulit Kayu', 'Mama Yulinus', 'Wamena, Papua', 420000, 96, 5],
            [4, 'Mangkuk Saji Kayu Jati Utuh Polesan Lilin', 'Pak Joko', 'Jepara, Jawa Tengah', 230000, 86, 8],
        ];

        foreach ($products as [$cat, $name, $artisan, $origin, $price, $ft, $stock]) {
            Product::create([
                'category_id' => $cat,
                'name' => $name,
                'slug' => Str::slug($name),
                'artisan_name' => $artisan,
                'origin' => $origin,
                'price' => $price,
                'fair_trade_percent' => $ft,
                'stock' => $stock,
            ]);
        }
    }
}
