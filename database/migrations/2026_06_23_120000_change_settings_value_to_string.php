<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Settings hold arbitrary string values (see SettingController::set, which
     * assigns a value from the URL). An earlier migration narrowed the column
     * to a boolean, which rejects those strings under MySQL strict mode.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('value')->default('')->change();
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('value')->default(false)->change();
        });
    }
};
