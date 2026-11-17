<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migrasi sementara (stub) untuk tabel ketergantungan fitur Buyer Browsing & Wishlist.
     * Disesuaikan 100% dengan definisi tabel pada database/uemkaem.sql serta kebutuhan PBI #22-#25.
     * Menggunakan pengecekan Schema::hasTable dan Schema::hasColumn agar aman saat merge branch.
     */
    public function up(): void
    {
        if (! Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('tagline')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
                $table->binary('image')->nullable();
                $table->string('badge')->nullable();
                $table->string('group')->nullable();
                $table->string('regions')->nullable();
                $table->text('tags')->nullable();
                $table->unsignedInteger('artisans_count')->nullable();
            });
        }

        if (! Schema::hasTable('regions')) {
            Schema::create('regions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('province')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('artisans')) {
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
        }

        if (! Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
                $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
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
        } else {
            Schema::table('products', function (Blueprint $table) {
                if (! Schema::hasColumn('products', 'region_id')) {
                    $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
                }
                if (! Schema::hasColumn('products', 'artisan_id')) {
                    $table->foreignId('artisan_id')->nullable()->constrained('artisans')->nullOnDelete();
                }
                if (! Schema::hasColumn('products', 'description')) {
                    $table->text('description')->nullable();
                }
                if (! Schema::hasColumn('products', 'image')) {
                    $table->string('image')->nullable();
                }
                if (! Schema::hasColumn('products', 'status')) {
                    $table->string('status')->default('published');
                }
            });
        }

        if (! Schema::hasTable('fair_prices')) {
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
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fair_prices');
        Schema::dropIfExists('artisans');
        Schema::dropIfExists('regions');
    }
};
