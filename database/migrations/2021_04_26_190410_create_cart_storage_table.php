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
        Schema::create('cart_storage', function (Blueprint $table) {
            $table->string('id')->index();
            $table->longText('cart_data');
            $table->timestamps();

            $table->bigInteger('vendor_id');
            $table->bigInteger('user_id');
            $table->integer('type');
            $table->string('receipt_number');

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_storage');
    }
};
