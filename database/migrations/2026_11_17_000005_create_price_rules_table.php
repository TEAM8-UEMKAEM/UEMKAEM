<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('material_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('min_price'); // sama tipe dengan products.price
            $table->unsignedInteger('max_price');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            // satu aturan per kombinasi: mencegah aturan ganda yang bertentangan
            $table->unique(['category_id', 'material_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_rules');
    }
};
