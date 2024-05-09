<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoServicio;
use App\Utility\Response;
use Illuminate\Http\Request;

class TipoServicioController extends Controller
{
    public function listar()
    {
        $tipos = TipoServicio::where('estado', 'ACTIVO')->get();
        $respuesta = new Response();
        $respuesta->result = true;
        $respuesta->data = $tipos;
        return response()->json($respuesta);
    }
}
