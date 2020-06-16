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
            $table->integer('presentation');
            $table->string('diakcode');
            $table->boolean('present');
            $table->timestamps();

            $table->foreign('diakcode')->references('code')->on('students');
            $table->foreign('presentation')->references('id')->on('presentations');
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
