<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('google_id',64)->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->integer('class_id')->nullable();

            $table->rememberToken();
            $table->timestamps();

            $table->foreign('class_id')->references('id')->on('ejg_classes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
