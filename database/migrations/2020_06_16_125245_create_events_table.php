<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
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

            $table->string('code')->unique()->comment('remember token for event-organiser');

            $table->text('name');
            $table->text('description')->nullable();
            $table->integer('location_id')->nullable();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->tinyInteger('weight')->default(1)->nullable()->comment('shhould be NULL if presentation');
            $table->integer('image_id')->nullable();

            $table->integer('organiser_id')->nullable();
            $table->text('organiser_name')->nullable()->comment('should be NULL if not presentation');

            $table->tinyInteger('capacity')->nullable()->comment('null if unlimited');
            $table->tinyInteger('slot')->nullable()->comment('should be NULL if not presentation');

            $table->boolean('is_presentation')->comment('true if event is a presentation');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('organiser_id')->references('id')->on('users')->onDelete('cascade');

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
}
