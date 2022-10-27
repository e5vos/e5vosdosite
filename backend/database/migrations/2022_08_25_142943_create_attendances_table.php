<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\{
    User,
    Team,
    Event
};
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->comment('null if team attendance=>team_code cant be null');
            $table->foreignIdFor(Team::class)->nullable()->comment('null if user attendance=>user_id cant be null');
            $table->foreignIdFor(Event::class);
            $table->boolean('is_present')->default(false);
            $table->tinyInteger('rank')->nullable()->comment('null if not competition or no rank achieved');
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
        Schema::dropIfExists('attendance');
    }
};
