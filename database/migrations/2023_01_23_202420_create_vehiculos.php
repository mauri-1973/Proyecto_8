<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users_id_veh');
            $table->string('marca');
            $table->string('modelo');
            $table->string('patente');
            $table->unsignedBigInteger('annio');
            $table->unsignedBigInteger('precio');
            $table->timestamps();
            $table->time('deleted_at')->nullable();
        });
        Schema::table('vehiculos', function (Blueprint $table){
            $table->foreign('users_id_veh')->references('id')->on('users')->onDelete('cascade');           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehiculos');
    }
}
