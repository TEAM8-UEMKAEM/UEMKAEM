<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $columns = [
        'tagline'        => ['string', 255],
        'badge'          => ['string', 255],
        'group'          => ['string', 255],
        'regions'        => ['string', 255],
        'tags'           => ['text', null],
        'artisans_count' => ['unsignedInteger', null],
    ];

    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            foreach ($this->columns as $name => [$type, $length]) {
                if (Schema::hasColumn('categories', $name)) {
                    continue; // kolom sudah ada, lewati
                }

                $column = $length ? $table->{$type}($name, $length) : $table->{$type}($name);
                $column->nullable();
            }
        });
    }

    public function down(): void
    {
        // Sengaja kosong: hanya kolom yang belum ada yang ditambahkan,
        // jadi tidak aman menghapus kolom secara otomatis.
    }
};
