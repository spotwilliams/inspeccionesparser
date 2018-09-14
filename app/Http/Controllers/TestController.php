<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Models\Inspeccion;
use Maatwebsite\Excel\Collections\CellCollection;
use Maatwebsite\Excel\Facades\Excel;
use \Illuminate\Http\Request;
use Maatwebsite\Excel\Readers\LaravelExcelReader;

/**
 * Description of TestController
 *
 * @author Debi
 */
class TestController
{
    /**
     * Muestra el index del sitio
     * @return mixed
     */
    public function test()
    {
        return view('test');
    }
    
    /**
     * Recibe los datos del form. Entre ellos el select y el file
     * @param Request $request
     * @return mixed
     */
    public function upload(Request $request)
    {
        if (!$request->file()) {
            // Esta validacion se puede hacer con Laravel.
            return back()->with('message-danger', 'No se ha cargado ningún archivo');
        }
        
        $input = $request->all();
        
        /** @var string $archivo Es la ruta donde esta el archivo. */
        // la funcion guardarArchivo ubica el mismo en storage/app/uploads
        $archivo = $this->guardarArchivo($request, $input['area']);
        
        switch ($input['area']) {
            case 'APrA':
                $this->ingresarInspeccionAPrA($archivo);
                break;
            case 'EP':
                $this->ingresarInspeccionEP($archivo);
                break;
            case 'Trabajo':
                $this->ingresarInspeccionT($archivo);
                break;
            case 'GOCHU':
                $this->ingresarInspeccionG($archivo);
                break;
        }
    }
    
    /**
     * @param Request $request
     * @param string $area
     * @return string Devuelve la ubicacion del archivo
     */
    protected function guardarArchivo(Request $request, $area = 'area')
    {
        // Le damos un nombre al archivo unico para que no se repita
        $nombre = 'inspecciones_' . $area . '_' . uniqid() . '.' . $request->file('archivo')->extension();
        
        $request->file('archivo')->storeAs('uploads', $nombre);
        
        $ubicacion = 'storage/app/uploads/' . $nombre;
        
        return $ubicacion;
    }
    
    public function ingresarInspeccionAPrA($archivo)
    {
        Excel::load($archivo, function (LaravelExcelReader $reader) {

            
            $reader->each(function (CellCollection $row) {
                
                $inspeccion               = new Inspeccion();
                $inspeccion->ID           = $row->id;
                $inspeccion->dependencia  = $row->Dependencia;
                $inspeccion->area         = $row->$input['area'];
                $inspeccion->fecha        = $row->fecha_inspeccion;
                $inspeccion->motivo       = $row->motivo;
                $inspeccion->nombreCalle  = $row->NombreCalle;
                $inspeccion->numeroPuerta = $row->NumeroPuerta;
                $inspeccion->seccion      = $row->Seccion;
                $inspeccion->manzana      = $row->Manzana;
                $inspeccion->parcela      = $row->Parcela;
                
                $SMPvalida = $this->busquedaSMP($inspeccion->Seccion, $inspeccion->Manzana, $inspeccion->Parcela);
                if ($SMPvalida != true) {
                    return "SMP no válida";
                }
                
                $inspeccion->domicilio            = $row->Domicilio;
                $inspeccion->pisoDepartamento     = $row->pisoDepartamento;
                $inspeccion->otros                = $row->Otros;
                $inspeccion->nroPartidaMatriz     = $row->NroPartidaMatriz;
                $inspeccion->nroPartidaHorizontal = $row->NroPartidaHorizontal;
                $inspeccion->CUIT                 = $row->Cuit;
                $inspeccion->razonSocial          = $row->RazonSocial;
                
                $inspeccion->save();
            });
        });
        
        return "Terminado";
    }
    
    public function ingresarInspeccionEP($archivo)
    {
        {
            \Excel::load($request->archivo, function ($reader) {
                
                $excel       = $reader->get();
                $excelheader = $excel->first()->keys()->toArray();
                $reader->each(function ($row) {
                    
                    $inspeccion               = new Inspeccion;
                    $inspeccion->ID           = $row->ID;
                    $inspeccion->dependencia  = $row->Dependencia;
                    $inspeccion->area         = $row->$input['area'];
                    $inspeccion->fecha        = $row->Fecha_Inspeccion;
                    $inspeccion->motivo       = $row->Motivo;
                    $inspeccion->nombreCalle  = $row->NombreCalle;
                    $inspeccion->numeroPuerta = $row->NumeroPuerta;
                    
                    $calleOK = arregloCalle($inspeccion->nombreCalle, $inspeccion->numeroPuerta);
                    
                    busquedaSMPCalle($calleOK, $inspeccion->numeroPuerta);
                    
                    if ($SMPvalida != true) {
                        return "SMP no válida";
                    } else {
                        $inspeccion->domicilio = $row->Domicilio;
                    }
                    $inspeccion->pisoDepartamento     = $row->pisoDepartamento;
                    $inspeccion->otros                = $row->Otros;
                    $inspeccion->nroPartidaMatriz     = $row->NroPartidaMatriz;
                    $inspeccion->nroPartidaHorizontal = $row->NroPartidaHorizontal;
                    $inspeccion->CUIT                 = $row->Cuit;
                    $inspeccion->razonSocial          = $row->RazonSocial;
                    
                    $inspeccion->save();
                });
            });
            
            return "Terminado";
        }
    }
    
    public function ingresarInspeccionT($archivo)
    {
    }
    
    public function ingresarInspeccionG($archivo)
    {
    }
    
    public function busquedaSMPCalle($calle, $altura)
    {
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
    
    public function arregloCalle($calle, $altura)
    {
        
        $calleValidada = SMP::where('calle_1', '=', $calle)->first();
        if ($calleValidada === null) {
            $calleValidada = elegirCalle($calle);
        } else {
            $calleValidada = true;
        }
        
        
    }
    
    public function busquedaSMP($seccion, $manzana, $parcela)
    {
        
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
    
    public function elegirCalle($calle)
    {
        
        $smp = SMP::where('seccion', '=', $seccion)
            ->where('manzana', '=', $manzana)
            ->where('parcela', '=', $pacela)
            ->first();
    }
}
