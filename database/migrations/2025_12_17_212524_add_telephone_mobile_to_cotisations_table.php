<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cotisations', function (Blueprint $table) {
            $table->string('telephone_mobile', 20)->nullable()->after('type_paiement');
        });
    }

    public function down(): void
    {
        Schema::table('cotisations', function (Blueprint $table) {
            $table->dropColumn('telephone_mobile');
        });
    }
};