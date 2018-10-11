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
use App\Models\SMP;
use \Maatwebsite\Excel\Collections\RowCollection;

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
        $archivo = $this->guardarArchivo($request);
        
        $this->ingresarInspeccion($archivo);
        $this->exportExcel();
    }
    
    /**
     * @param Request $request
     * @param string $area
     * @return string Devuelve la ubicacion del archivo
     */
    protected function guardarArchivo(Request $request)
    {
        // Le damos un nombre al archivo unico para que no se repita
        $nombre = 'inspecciones_' . uniqid() . '.' . $request->file('archivo')->extension();
        
        $request->file('archivo')->storeAs('uploads', $nombre);
        
        $ubicacion = 'storage/app/uploads/' . $nombre;
        
        return $ubicacion;
    }
    
    public function ingresarInspeccion($archivo)
    {
        
        Excel::load($archivo, function (LaravelExcelReader $reader) {
            
            $reader->chunk(10, function (RowCollection $rowCollection) {
                
                $rowCollection->each(function ($row) {
                    $inspeccion                = new Inspeccion();
                    $inspeccion->id            = $row->id;
                    $inspeccion->dependencia   = $row->dependencia;
                    $inspeccion->area          = $row->area;
                    $inspeccion->fecha         = $row->fecha_inspeccion;
                    $inspeccion->motivo        = $row->motivo;
                    $inspeccion->nombre_calle  = $row->nombrecalle;
                    $inspeccion->numero_puerta = $row->numeropuerta;
                    
                    $SMPvalida = $this->busquedaSMPCalle($inspeccion->nombre_calle, $inspeccion->numero_puerta);
                    
                    if ($SMPvalida != null) {
                        $inspeccion->seccion = $SMPvalida->seccion;
                        $inspeccion->manzana = $SMPvalida->manzana;
                        $inspeccion->parcela = $SMPvalida->parcela;
                        
                    }
                    $inspeccion->domicilio          = $row->domicilio;
                    $inspeccion->piso_departamento  = $row->pisodepartamento;
                    $inspeccion->otros              = $row->otros;
                    $inspeccion->partida_matriz     = $row->nropartidamatriz;
                    $inspeccion->partida_horizontal = $row->nropartidahorizontal;
                    $inspeccion->CUIT               = $row->cuit;
                    $inspeccion->razon_social       = $row->razonsocial;
                    $inspeccion->save();
                    
                });
            });
        });
        
        return "Terminado";
    }
    
    public function busquedaSMPCalle($calle, $altura)
    {
        $smp = SMP::where('calle_1', '=', $calle)
            ->where('num', '=', $altura)
            ->first();
        
        return $smp;
    }
    
    public function exportExcel()
    {
        // dd(Inspeccion::first());
        /**
         * toma en cuenta que para ver los mismos
         * datos debemos hacer la misma consulta
         **/
        Excel::create('Export', function ($excel) {
            $excel->sheet('Excel sheet', function ($sheet) {
                //otra opción -> $products = Product::select('name')->get();
                $inspecciones = Inspeccion::all();
                $sheet->fromArray($inspecciones);
                $sheet->setOrientation('landscape');
            });
        })->export('xls');
    }
}
