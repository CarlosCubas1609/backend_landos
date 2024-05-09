<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoPago;
use App\Utility\Response;
use Illuminate\Http\Request;

class TipoPagoController extends Controller
{
    public function listar() {
        $respuesta = new Response();
        $tipos = TipoPago::where('estado', 'ACTIVO')->get();
        $respuesta->result = true;
        $respuesta->data = $tipos;
        return response()->json($respuesta);
    }
}
