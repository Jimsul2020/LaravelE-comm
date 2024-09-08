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
        schema::table('orders', function (Blueprint $table) {
            $table->string('coupon_code')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::table('orders', function (Blueprint $table) {
            $table->integer('coupon_code')->change();
        });
    }
};
