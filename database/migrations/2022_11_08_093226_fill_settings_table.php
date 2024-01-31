<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('settings')->truncate();
        $keys = array_column(json_decode(file_get_contents(__DIR__.'/../defaultsettings.json')), 'key');
        foreach ($keys as $key) {
            DB::table('settings')->insert(['key' => $key, 'value' => false, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('settings')->truncate();
    }
};
