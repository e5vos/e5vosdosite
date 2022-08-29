    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\EjgClassType;

return new class extends Migration
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
            $table->string('e5code',13)->unique()->nullable();
            $table->string('name');
            $table->string('email',73)->unique();//64 max namelength + @e5vos.hu length
            $table->string('google_id')->unique()->nullable()->comment('null befor first login');
            $table->enum('ejg_class',array_column(EjgClassType::cases(),'value'))->nullable();
            $table->string('img_url')->nullable();
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
        Schema::dropIfExists('users');
    }
};
