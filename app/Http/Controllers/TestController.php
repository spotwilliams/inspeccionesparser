<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

/**
 * Description of TestController
 *
 * @author Debi
 */
class TestController {

    public function test() {
        return view('test');
    }

    public function upload(\Illuminate\Http\Request $request) {
        $input = $request->all();

        $input['area'];

        if ($input['archivo'] == null) {
            return back()->with('message-danger', 'No se ha cargado ningún archivo');
        }


        switch ($input['area']) {
            case 'APrA':
                $this->ingresarInspeccionAPrA($input['archivo']);
                break;
            case 'EP':
                $this->ingresarInspeccionEP($input['archivo']);
                break;
            case 'Trabajo':
                $this->ingresarInspeccionT($input['archivo']);
                break;
            case 'GOCHU':
                $this->ingresarInspeccionG($input['archivo']);
                break;
        }
    }

    public function ingresarInspeccionAPrA() { {
            \Excel::load($request->archivo, function($reader) {

                $excel = $reader->get();

                $reader->each(function($row) {
                    $inspeccion = new Inspeccion;
                    $inspeccion->ID = $row->ID;
                    $inspeccion->dependencia = $row->Dependencia;
                    $inspeccion->area = $row->$input['area'];
                    $inspeccion->fecha = $row->Fecha_Inspeccion;
                    $inspeccion->motivo = $row->Motivo;
                    $inspeccion->nombreCalle = $row->NombreCalle;
                    $inspeccion->numeroPuerta = $row->NumeroPuerta;
                    $inspeccion->seccion = $row->Seccion;
                    $inspeccion->manzana = $row->Manzana;
                    $inspeccion->parcela = $row->Parcela;

                    $SMPvalida = $this->busquedaSMP($inspeccion->Seccion, $inspeccion->Manzana, $inspeccion->Parcela);
                    if ($SMPvalida != true) {
                        return "SMP no válida";
                    }

                    $inspeccion->domicilio = $row->Domicilio;
                    $inspeccion->pisoDepartamento = $row->pisoDepartamento;
                    $inspeccion->otros = $row->Otros;
                    $inspeccion->nroPartidaMatriz = $row->NroPartidaMatriz;
                    $inspeccion->nroPartidaHorizontal = $row->NroPartidaHorizontal;
                    $inspeccion->CUIT = $row->Cuit;
                    $inspeccion->razonSocial = $row->RazonSocial;

                    $inspeccion->save();
                });
            });

            return "Terminado";
        }
    }

    public function ingresarInspeccionEP() { {
            \Excel::load($request->archivo, function($reader) {

                $excel = $reader->get();
                $excelheader = $excel->first()->keys()->toArray();
                $reader->each(function($row) {

                    $inspeccion = new Inspeccion;
                    $inspeccion->ID = $row->ID;
                    $inspeccion->dependencia = $row->Dependencia;
                    $inspeccion->area = $row->$input['area'];
                    $inspeccion->fecha = $row->Fecha_Inspeccion;
                    $inspeccion->motivo = $row->Motivo;
                    $inspeccion->nombreCalle = $row->NombreCalle;
                    $inspeccion->numeroPuerta = $row->NumeroPuerta;

                    $calleOK = arregloCalle($inspeccion->nombreCalle,$inspeccion->numeroPuerta);
                    
                    busquedaSMPCalle($calleOK,$inspeccion->numeroPuerta);
                    
                    if ($SMPvalida != true) {
                        return "SMP no válida";
                    }
                    else
                      

                    $inspeccion->domicilio = $row->Domicilio;
                    $inspeccion->pisoDepartamento = $row->pisoDepartamento;
                    $inspeccion->otros = $row->Otros;
                    $inspeccion->nroPartidaMatriz = $row->NroPartidaMatriz;
                    $inspeccion->nroPartidaHorizontal = $row->NroPartidaHorizontal;
                    $inspeccion->CUIT = $row->Cuit;
                    $inspeccion->razonSocial = $row->RazonSocial;

                    $inspeccion->save();
                });
            });

            return "Terminado";
        }
    }

    public function busquedaSMPCalle($calle, $altura){
        $smp = SMP::where('calle_1', '=', $calle)
                ->where('num', '=', $altura)
                ->first();
        
         if ($smp === null) {
            $smp = false;
        } else {
            $smp = true;
        }
        return $smp;
    }
    
    public function arregloCalle($calle, $altura){
        
        $calleValidada = SMP::where('calle_1', '=', $calle)->first();
        if ($calleValidada === null) {
            $calleValidada = elegirCalle($calle);
        } else {
            $calleValidada = true;
        }
        
        
    }
    
    public function busquedaSMP($seccion, $manzana, $parcela) {

        $smp = SMP::where('seccion', '=', $seccion)
                ->where('manzana', '=', $manzana)
                ->where('parcela', '=', $pacela)
                ->first();
        if ($smp === null) {
            $smp = false;
        } else {
            $smp = true;
        }
        return $smp;
    }
    
    public function elegirCalle($calle){
        
        $smp = SMP::where('seccion', '=', $seccion)
                ->where('manzana', '=', $manzana)
                ->where('parcela', '=', $pacela)
                ->first();
    }
}
