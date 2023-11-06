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
        //DB::statement('ALTER TABLE users ADD CONSTRAINT `users_e5code_format` CHECK(e5code RLIKE "20[0-9]{2}[A-FN][0-9]{2}EJG[0-9]{3}$")');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //DB::statement('ALTER TABLE users DROP CONSTRAINT `users_e5code_format`');
    }
};
