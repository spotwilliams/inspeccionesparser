<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of Inspeccion
 *
 * @author Debi
 */
class Inspeccion extends Model
{
    protected $table = 'inspecciones';
    
    protected $guarded    = [];
    public    $timestamps = false;
    
    public $hidden
        = [
            'transaccionId',
        ];
}
