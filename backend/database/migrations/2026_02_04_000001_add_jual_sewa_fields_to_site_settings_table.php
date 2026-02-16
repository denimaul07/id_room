<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('bannerSewa')->nullable();
            $table->string('colorSewa')->nullable();
            $table->string('bannerJual')->nullable();
            $table->string('colorJual')->nullable();
            $table->string('bannerSewaDetail')->nullable();
            $table->string('colorSewaDetail')->nullable();
            $table->string('bannerJualDetail')->nullable();
            $table->string('colorJualDetail')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'bannerSewa',
                'colorSewa',
                'bannerJual',
                'colorJual',
                'bannerSewaDetail',
                'colorSewaDetail',
                'bannerJualDetail',
                'colorJualDetail'
            ]);
        });
    }
};
