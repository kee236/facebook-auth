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
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('image')->nullable();
            $table->string('title')->nullable();
            $table->string('sku_name')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('available', ['enable', 'disable'])->default('enable');
            $table->enum('stock', ['in stock', 'out of stock', 'limited quantity'])->nullable();
            $table->text('messenger_keywords')->nullable();
            $table->text('auto_reply_comments')->nullable();
            $table->text('auto_reply_comment')->nullable();
            // $table->foreign('category_id')->references('id')->on('categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};
