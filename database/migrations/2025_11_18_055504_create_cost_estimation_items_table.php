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
        Schema::create('cost_estimation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cost_estimation_id')->constrained('cost_estimations')->cascadeOnDelete();
            $table->foreignId('service_part_id')->nullable()->constrained('service_parts')->nullOnDelete();
            $table->string('part_name');
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('provider_price', 10, 2)->default(0);
            $table->integer('qty')->default(1);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_estimation_items');
    }
};
