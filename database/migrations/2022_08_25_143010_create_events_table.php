<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('decription');
            $table->foreignIdFor(Slot::class);
            $table->enum('signup_type',['team','student', 'team_student'])->nullable()->comment('null if no signup required');
            $table->dateTime('signup_deadline');
            $table->tinyInteger('capacity')->nullable()->comment('null if infinite capacity');
            $table->string('img_url')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
