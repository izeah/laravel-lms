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
        Schema::create('issue_rules', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->unsignedTinyInteger('role_id');
            $table->tinyInteger('max_borrow_item');
            $table->tinyInteger('max_borrow_day');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_rules');
    }
};
