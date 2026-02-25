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
        Schema::table('locales', function (Blueprint $table) {
            $table->string('operacion')->default('alquiler')->after('estado');
            $table->decimal('area', 8, 2)->nullable()->after('operacion');
            $table->string('distrito')->nullable()->after('area');
            $table->json('imagenes')->nullable()->after('distrito');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locales', function (Blueprint $table) {
            $table->dropColumn(['operacion', 'area', 'distrito', 'imagenes']);
        });
    }
};
