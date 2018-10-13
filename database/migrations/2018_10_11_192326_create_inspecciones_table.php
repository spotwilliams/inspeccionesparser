<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInspeccionesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inspecciones', function(Blueprint $table)
		{
			$table->text('dependencia', 65535)->nullable();
			$table->text('area', 65535)->nullable();
			$table->string('id', 200)->nullable();
			$table->string('fecha', 10)->nullable();
			$table->text('motivo', 65535)->nullable();
			$table->text('nombre_calle', 65535)->nullable();
			$table->integer('numero_puerta')->nullable();
			$table->string('seccion', 10)->nullable();
			$table->string('manzana', 10)->nullable();
			$table->string('parcela', 10)->nullable();
			$table->string('domicilio', 100)->nullable();
			$table->integer('piso_departamento')->nullable();
			$table->integer('otros')->nullable();
			$table->string('partida_matriz', 20)->nullable();
			$table->string('partida_horizontal', 20)->nullable();
			$table->string('cuit', 20)->nullable();
			$table->string('razon_social', 100)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('inspecciones');
	}

}
