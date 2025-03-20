<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('api_endpoint');
            $table->text('request_payload');
            $table->text('response_payload');
            $table->integer('status_code');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_logs');
        Schema::dropIfExists('orders');
    }
};
