<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('retraits', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas avant de les ajouter
            if (!Schema::hasColumn('retraits', 'type_paiement')) {
                $table->enum('type_paiement', ['especes', 'mobile_money'])->default('especes')->after('motif');
            }
            if (!Schema::hasColumn('retraits', 'telephone_mobile')) {
                $table->string('telephone_mobile', 20)->nullable()->after('type_paiement');
            }
        });
    }

    public function down(): void
    {
        Schema::table('retraits', function (Blueprint $table) {
            if (Schema::hasColumn('retraits', 'type_paiement')) {
                $table->dropColumn('type_paiement');
            }
            if (Schema::hasColumn('retraits', 'telephone_mobile')) {
                $table->dropColumn('telephone_mobile');
            }
        });
    }
};