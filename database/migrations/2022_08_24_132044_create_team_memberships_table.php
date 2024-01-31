<?php

use App\Helpers\MembershipType;
use App\Models\Team;
use App\Models\User;
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
        Schema::create('team_memberships', function (Blueprint $table) {
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Team::class);
            $table->enum('role', array_column(MembershipType::cases(), 'value'));
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
        Schema::dropIfExists('team_members');
    }
};
