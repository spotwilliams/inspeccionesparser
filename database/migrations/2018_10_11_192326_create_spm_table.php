<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSpmTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spm', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('seccion', 20);
            $table->string('manzana', 20);
            $table->string('parcela', 20);
            $table->string('calle_1', 100);
            $table->integer('num');
        });
    }
    
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('spm');
    }
    
}
