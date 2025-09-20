<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('multi_payments', function (Blueprint $table) {
            $table->string('specific_level')->nullable()->after('school_level'); // For books - class level
            $table->json('available_sizes')->nullable()->after('specific_level'); // For uniforms
            $table->string('supplier')->nullable()->after('available_sizes'); // Supplier/vendor info
            $table->integer('stock_quantity')->nullable()->after('supplier'); // Stock management
            $table->boolean('track_stock')->default(false)->after('stock_quantity'); // Enable stock tracking

            // Update category enum to match form options
            $table->dropColumn('category');
        });

        Schema::table('multi_payments', function (Blueprint $table) {
            $table->enum('category', ['books', 'uniforms', 'supplies', 'others'])->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('multi_payments', function (Blueprint $table) {
            $table->dropColumn(['specific_level', 'available_sizes', 'supplier', 'stock_quantity', 'track_stock']);
            $table->dropColumn('category');
        });

        Schema::table('multi_payments', function (Blueprint $table) {
            $table->string('category')->after('name'); // Restore original
        });
    }
};
