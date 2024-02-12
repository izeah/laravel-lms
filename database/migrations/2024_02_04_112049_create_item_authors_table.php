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
        Schema::create('item_authors', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('item_id');
            $table->unsignedSmallInteger('author_id');

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_authors');
    }
};
