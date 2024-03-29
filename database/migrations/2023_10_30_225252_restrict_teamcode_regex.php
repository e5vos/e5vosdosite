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
        DB::statement('ALTER TABLE `teams` ADD CONSTRAINT `teams_code_ten_long_non_numeric` CHECK(code RLIKE"[a-zA-Z]{1,10}$")');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `teams` DROP CONSTRAINT `teams_code_ten_long_non_numeric`');
    }
};
