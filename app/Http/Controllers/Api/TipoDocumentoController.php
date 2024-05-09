<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoDocumento;
use App\Utility\Response;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    public function listar()
    {
        $tipos = TipoDocumento::all();
        $respuesta = new Response();
        $respuesta->result = true;
        $respuesta->data = $tipos;
        return response()->json($respuesta);
    }
}
