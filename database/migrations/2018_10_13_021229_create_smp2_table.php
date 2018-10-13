<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmp2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smp2', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seccion', 20);
            $table->string('manzana', 20);
            $table->string('parcela', 20);
            $table->string('calle_1', 100);
            $table->integer('num');
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
        Schema::dropIfExists('smp2');
    }
}
