<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Slot;
use App\Models\Location;

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
            $table->text('description');
            $table->foreignIdFor(Slot::class)->nullable()->comment('null if event is not in a slot');
            $table->foreignIdFor(Location::class)->default(0)->comment('0 if event is not location specific');
            $table->enum('signup_type', ['team', 'user', 'team_user'])->nullable()->comment('null: signup unrequired');
            $table->boolean('is_competition')->default(false);
            $table->dateTime('signup_deadline')->nullable();
            $table->string('organiser')->nullable();
            $table->tinyInteger('capacity')->nullable()->comment('null if infinite capacity');
            $table->string('img_url')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->softDeletes();
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
