<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genero;
use App\Utility\Response;
use Illuminate\Http\Request;

class GeneroController extends Controller
{
    public function listar() {
        $generos = Genero::all();
        $respuesta = new Response();
        $respuesta->result = true;
        $respuesta->data = $generos;
        return response()->json($respuesta);
    }
}
