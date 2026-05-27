<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Superseded by 2026_05_22_000003_create_orders_table.php.
        // This migration originally ran before services existed.
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
