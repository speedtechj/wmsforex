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
        Schema::create('skiddinginfos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('skidno');
            $table->foreignId('booking_id')->nullable()->constrained();
            $table->string('virtual_invoice');
            $table->foreignId('batch_id');
            $table->foreignId('boxtype_id')->nullable();
            $table->boolean('is_encode')->default(false);
            $table->foreignId('user_id')->constrained();
            $table->bigInteger('quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skiddinginfos');
    }
};
