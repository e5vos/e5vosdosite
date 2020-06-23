<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresentationSignupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presentation_signups', function (Blueprint $table) {
            $table->id();
            $table->integer('presentation_id');
            $table->integer('student_id');
            $table->boolean('present')->default(false);
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('presentation_id')->references('id')->on('presentations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presentation_signups');
    }
}
