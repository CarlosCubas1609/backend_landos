<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Orden;
use App\Utility\Response;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReporteController extends Controller
{
    public function cajaDiaria($fecha) 
    {
        try {
            Log::info($fecha);
            $ordenes = Orden::where(DB::raw("CONVERT(created_at,date)"), $fecha)->where('estado_pago', 'PAGADA')->where('estado', 'ACTIVO')->get();

            $pdf = PDF::loadview('reportes.caja_diaria', [
                    'ordenes' => $ordenes,
                    'fecha' => $fecha,
                ])->setPaper('a4')->setWarnings(false);
            if (!file_exists(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'cajas'))) {
                mkdir(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'cajas'));
            }
            
            Storage::disk('cajas')->put($fecha . ".pdf", $pdf->output());
            Log::info("Se creo el pdf ".$fecha);
            $respuesta = new Response();
            $respuesta->result = true;
            $respuesta->mensaje = "Pdf creado";
            $respuesta->data = "";
            return response()->json($respuesta);
        }
        catch(Exception $e)
        {
            $respuesta = new Response();
            $respuesta->result = false;
            $respuesta->mensaje = $e->getMessage();
            $respuesta->data = "";
            Log::info($e->getMessage());
            return response()->json($respuesta);
        }
    }
}
