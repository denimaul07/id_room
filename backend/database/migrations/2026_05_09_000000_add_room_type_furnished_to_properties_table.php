<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('furnished')->nullable()->after('listing_type');
            // Values: 'full_furnished', 'semi_furnished', 'unfurnished'
        });

        Schema::table('room_subs', function (Blueprint $table) {
            $table->string('room_type')->nullable()->after('name_room');
            // Values: 'studio', '1br', '2br', '3br', etc.
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('furnished');
        });

        Schema::table('room_subs', function (Blueprint $table) {
            $table->dropColumn('room_type');
        });
    }
};
