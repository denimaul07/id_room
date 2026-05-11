<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wa_settings', function (Blueprint $table) {
            $table->string('media_video_url')->nullable()->after('media_url');
            $table->string('media_document_url')->nullable()->after('media_video_url');
        });
    }

    public function down()
    {
        Schema::table('wa_settings', function (Blueprint $table) {
            $table->dropColumn(['media_video_url', 'media_document_url']);
        });
    }
};
