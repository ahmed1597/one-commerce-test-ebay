<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ebay_inventory_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ebay_connection_id')->index();
            $table->string('sku')->index();

            $table->string('title')->nullable();
            $table->string('condition')->nullable();
            $table->unsignedInteger('quantity')->default(0);

            $table->json('raw')->nullable();
            $table->timestamp('synced_at')->nullable();

            $table->timestamps();

            $table->unique(['ebay_connection_id', 'sku']);

            $table->foreign('ebay_connection_id')
                ->references('id')->on('ebay_connections')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebay_inventory_items');
    }
};
