<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionIdToInspecciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inspecciones', function (Blueprint $table) {
            $table->string('transaccionId', 400)->nullable();
            
            $table->index('transaccionId', 'transaccion');
        });
        
        Schema::table('spm', function (Blueprint $table) {
            
            $table->index(['calle_1', 'nro'], 'calle_nro');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inspecciones', function (Blueprint $table) {
            $table->dropIndex('transaccion');
        });
        
        Schema::table('spm', function (Blueprint $table) {
            
            $table->dropIndex('calle_nro');
        });
    }
}
