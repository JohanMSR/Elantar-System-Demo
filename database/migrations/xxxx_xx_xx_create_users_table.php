use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('tx_primer_nombre');
            $table->string('tx_segundo_nombre')->nullable();
            $table->string('tx_primer_apellido');
            $table->string('tx_segundo_apellido')->nullable();
            $table->string('tx_telefono');
            $table->string('tx_email')->unique();
            $table->string('tx_password');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('lenguaje_principal')->nullable();
            // Otros campos existentes...
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
} 