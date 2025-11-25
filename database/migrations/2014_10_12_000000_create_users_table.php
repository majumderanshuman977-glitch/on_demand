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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->enum('role', ['user', 'provider', 'admin']);
            $table->string('profile_image')->nullable();
            $table->text('address')->nullable();
            $table->string('kyc_document')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->foreignId('category_id')->constrained('categories')->nullOnDelete();
            $table->string('service_location')->nullable();
            $table->string('service_area')->nullable();
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
