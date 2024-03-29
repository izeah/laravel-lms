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
        Schema::create('publishers', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50);
            $table->string('email', 50)->unique();
            $table->string('address');
            $table->string('phone_number', 25)->unique();
            $table->string('website', 50)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publishers');
    }
};
