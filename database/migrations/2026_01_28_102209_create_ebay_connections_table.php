<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ebay_connections', function (Blueprint $table) {
            $table->id();

            $table->string('provider')->default('ebay');
            $table->string('env')->default('sandbox');

            $table->text('access_token');
            $table->timestamp('access_token_expires_at');

            $table->text('refresh_token');
            $table->timestamp('refresh_token_expires_at')->nullable();

            $table->timestamps();

            $table->index(['provider', 'env']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebay_connections');
    }
};
