<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_contacts', function (Blueprint $table) {
            // Tambah status 'queued' dan 'sending' ke kolom status
            // Ganti enum atau ubah kolom jika perlu:
            // Jika sudah pakai string, cukup pastikan value-nya bisa: queued, sending, sent, failed, pending
            
            // Tambah kolom error_message jika belum ada
            if (!Schema::hasColumn('campaign_contacts', 'error_message')) {
                $table->text('error_message')->nullable()->after('status');
            }

            // Jika status masih enum, ubah ke string supaya lebih fleksibel:
            // $table->string('status')->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('campaign_contacts', function (Blueprint $table) {
            $table->dropColumn('error_message');
        });
    }
};
