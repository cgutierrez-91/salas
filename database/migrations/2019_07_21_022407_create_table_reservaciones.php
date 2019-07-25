<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateTableReservaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservaciones', function (Blueprint $table) {
            $table->bigIncrements('reservacion_id');
            $table->Integer('sala')->unsigned();
            $table->Integer('cuenta')->unsigned();
            $table->dateTime('f_reserva');
            $table->dateTime('f_uso_desde');
            $table->dateTime('f_uso_hasta');
            $table->enum('estado', ['Pendiente', 'Autorizado','Rechazado','Cancelado'])->default('Pendiente');
            $table->text('observaciones');
            $table->foreign('reservaciones_ibfk_1')->references('sala')->on('salas')->onDelete('cascade');
            $table->foreign('reservaciones_ibfk_2')->references('cuenta')->on('cuentas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservaciones');
    }
}
