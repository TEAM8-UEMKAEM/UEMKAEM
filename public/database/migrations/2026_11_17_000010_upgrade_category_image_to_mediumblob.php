<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Kolom categories.image di database Anda bertipe BLOB (maks. 64 KB).
     * Migrasi ini menaikkannya ke MEDIUMBLOB (maks. 16 MB) agar gambar yang diunggah
     * lewat panel admin tidak terpotong. Data lama TIDAK berubah.
     * Hanya berjalan di MySQL dan hanya jika tipe sekarang tepat 'blob'.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $column = DB::selectOne("SHOW COLUMNS FROM categories LIKE 'image'");

        if ($column && strtolower($column->Type) === 'blob') {
            DB::statement('ALTER TABLE categories MODIFY image MEDIUMBLOB NULL');
        }
    }

    public function down(): void
    {
        // Sengaja kosong: memperkecil kolom bisa memotong data.
    }
};
