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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->string('base_currency');
            $table->string('quote_currency');
            $table->decimal('raw_price', 15, 6);
            $table->timestamps();
        });

        Schema::table('prices', function (Blueprint $table) {
            $table->unique(['base_currency', 'quote_currency', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
