<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wa_settings', function (Blueprint $table) {
            $table->string('media_url')->nullable()->after('access_token');
        });
    }

    public function down()
    {
        Schema::table('wa_settings', function (Blueprint $table) {
            $table->dropColumn('media_url');
        });
    }
};
