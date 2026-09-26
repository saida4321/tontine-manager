<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Types\Etat;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Table USERS
        Schema::table('users', function (Blueprint $table) {
            $table->enum('etat', Etat::toEnum())->default(Etat::ACTIF)->after('role');
        });

        // 2. Table CABINETS
        Schema::table('cabinets', function (Blueprint $table) {
            $table->enum('etat', Etat::toEnum())->default(Etat::ACTIF)->after('email');
        });

        // 3. Table CABINET_USER
        Schema::table('cabinet_user', function (Blueprint $table) {
            $table->enum('etat', Etat::toEnum())->default(Etat::ACTIF)->after('user_id');
        });

        // 4. Table CLIENTS
        Schema::table('clients', function (Blueprint $table) {
            $table->enum('etat', Etat::toEnum())->default(Etat::ACTIF)->after('solde');
        });

        // 5. Table COTISATIONS
        Schema::table('cotisations', function (Blueprint $table) {
            $table->enum('etat', Etat::toEnum())->default(Etat::ACTIF)->after('date_validation');
        });

        // 6. Table RETRAITS
        Schema::table('retraits', function (Blueprint $table) {
            $table->enum('etat', Etat::toEnum())->default(Etat::ACTIF)->after('date_approbation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('etat');
        });

        Schema::table('cabinets', function (Blueprint $table) {
            $table->dropColumn('etat');
        });

        Schema::table('cabinet_user', function (Blueprint $table) {
            $table->dropColumn('etat');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('etat');
        });

        Schema::table('cotisations', function (Blueprint $table) {
            $table->dropColumn('etat');
        });

        Schema::table('retraits', function (Blueprint $table) {
            $table->dropColumn('etat');
        });
    }
};