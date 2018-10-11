<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Models\Inspeccion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
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
        $transactionId = time() . uniqid(mt_rand(), false);
        
        if (!$request->file()) {
            // Esta validacion se puede hacer con Laravel.
            flash('No se ha cargado ningún archivo')->error();
            
            return back();
        }
        
        try {
            DB::beginTransaction();
            /** @var string $archivo Es la ruta donde esta el archivo. */
            // la funcion guardarArchivo ubica el mismo en storage/app/uploads
            $archivos = $this->guardarArchivo($request);
            
            foreach ($archivos as $archivo) {
                $this->ingresarInspeccion($archivo, $transactionId);
            }
            
            DB::commit();
            flash('Proceso realizado correctamente')->success();
            
            return redirect(route('exportarView', ['transactionId' => $transactionId]));
            
        } catch (\Exception $exception) {
            
            DB::rollBack();
            flash('Sucedio un error')->error($exception->getMessage());
            
            return redirect(route('index'));
        }
    }
    
    /**
     * @param Request $request
     * @return array
     */
    protected function guardarArchivo(Request $request)
    {
        $files = [];
        /** @var UploadedFile $file */
        foreach ($request->file('archivo') as $file) {
            
            // Le damos un nombre al archivo unico para que no se repita
            $nombre = 'inspecciones_' . uniqid() . '.' . $file->extension();
            
            $file->storeAs('uploads', $nombre);
            
            $ubicacion = 'storage/app/uploads/' . $nombre;
            $files[]   = $ubicacion;
        }
        
        return $files;
    }
    
    public function ingresarInspeccion($archivo, $transactionId)
    {
        
        Excel::load($archivo, function (LaravelExcelReader $reader) use ($transactionId) {
            
            $reader->chunk(10, function (RowCollection $rowCollection) use ($transactionId) {
                
                $rowCollection->each(function ($row) use ($transactionId) {
                    $inspeccion                = new Inspeccion();
                    $inspeccion->transaccionId = $transactionId;
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
    
    public function exportView($transactionId)
    {
        return view('download')
            ->with('transactionId', $transactionId);
    }
    
    public function exportExcel(Request $request)
    {
        /**
         * toma en cuenta que para ver los mismos
         * datos debemos hacer la misma consulta
         **/
        Excel::create('Export', function ($excel) use ($request) {
            $excel->sheet('Excel sheet', function ($sheet) use ($request) {
                $inspecciones = Inspeccion::where('transaccionId', '=', $request->input('transactionId'))
                    ->get();
                $sheet->fromArray($inspecciones);
                $sheet->setOrientation('landscape');
            });
        })->export('xls');
    }
}
