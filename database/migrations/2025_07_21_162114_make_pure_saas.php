<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $tables = ['banners', 'cart_storage', 'cities', 'variants_has_extras', 'variants', 'extras', 'options', 'order_has_items', 'items', 'localmenus', 'order_has_status', 'ratings', 'orders', 'paths', 'visits', 'tables', 'restoareas', 'simple_delivery_areas', 'sms_verifications', 'status', 'address'];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (config('settings.makePureSaaS', false)) {
            foreach ($this->tables as $key => $table) {
                Schema::dropIfExists($table);
            }
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
