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
        Schema::create('issue_items', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('issue_id');
            $table->unsignedSmallInteger('book_id');
            $table->date('borrow_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->string('status', 10);
            $table->timestamps();

            $table->foreign('issue_id')->references('id')->on('issues')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_items');
    }
};
