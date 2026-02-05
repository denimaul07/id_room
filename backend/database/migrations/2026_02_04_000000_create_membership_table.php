<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership', function (Blueprint $table) {
            $table->id();
            $table->string('odata', 50)->nullable();
            $table->string('title', 50);
            $table->text('desc')->nullable();
            $table->integer('price');
            $table->integer('duration');
            $table->integer('isActive');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership');
    }
};
