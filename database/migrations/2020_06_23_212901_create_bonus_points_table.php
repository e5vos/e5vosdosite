<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_points', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('class_id');
            $table->integer('point');
            $table->string('event')->default('E5N');

            $table->foreign('class_id')->references('id')->on('ejg_classes')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonus_points');
    }
}
