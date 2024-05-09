<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Utility\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ServicioController extends Controller
{
    public function listar()
    {
        $respuesta = new Response();
        $respuesta->result = true;
        $servicios = Servicio::where('estado', 'ACTIVO')->get();
        $respuesta->data = $servicios;
        return response()->json($respuesta);
    }

    public function store(Request $request)
    {
        $respuesta = new Response();
        $data = $request->all();
        Log::info($data);
        $rules = [
            'nombre' => [
                'required',
                Rule::unique('servicios', 'nombre')->where(function ($query) {
                    $query->whereIn('estado', ["ACTIVO"]);
                })
            ],
            'descripcion' => 'required',
            'precio' => 'required',
            'tipo_servicio_id' => 'required',

        ];
        $message = [
            'nombre.required' => 'El campo nombre es obligatorio',
            'nombre.unique' => 'El campo nombre debe de ser campo único.',
            'descripcion.required' => 'El campo descripcion es obligatorio',
            'precio.required' => 'El campo precio es obligatorio',
            'tipo_servicio_id.required' => 'El campo tipo de servicio es obligatorio',
        ];

        $validator =  Validator::make($data, $rules, $message);

        if ($validator->fails()) {
            $clase = $validator->getMessageBag()->toArray();
            $cadena = "";
            foreach ($clase as $clave => $valor) {
                $cadena =  $cadena . "$valor[0] ";
            }
            $respuesta->result = false;
            $respuesta->mensaje = $cadena;
            $respuesta->data = array('errors' => $validator->getMessageBag()->toArray());
            return response()->json($respuesta);
        }

        $servicio = new Servicio();
        $servicio->nombre = $request->nombre;
        $servicio->descripcion = $request->descripcion;
        $servicio->precio = $request->precio;
        $servicio->precio_oferta = $request->precio_oferta;
        $servicio->tipo_servicio_id = $request->tipo_servicio_id;
        $servicio->estado_oferta = $request->estado_oferta;
        $servicio->save();

        $respuesta->result = true;
        $respuesta->mensaje = "Servicio se registró con exito";
        $respuesta->data = "";

        return response()->json($respuesta);
    }

    public function update(Request $request, $id)
    {
        $respuesta = new Response();
        $data = $request->all();
        Log::info($data);
        $rules = [
            'nombre' => [
                'required',
                Rule::unique('servicios', 'nombre')->where(function ($query) {
                    $query->whereIn('estado', ["ACTIVO"]);
                })->ignore($id)
            ],
            'descripcion' => 'required',
            'precio' => 'required',
            'tipo_servicio_id' => 'required',

        ];
        $message = [
            'nombre.required' => 'El campo nombre es obligatorio',
            'nombre.unique' => 'El campo nombre debe de ser campo único.',
            'descripcion.required' => 'El campo descripcion es obligatorio',
            'precio.required' => 'El campo precio es obligatorio',
            'tipo_servicio_id.required' => 'El campo tipo de servicio es obligatorio',
        ];

        $validator =  Validator::make($data, $rules, $message);

        if ($validator->fails()) {
            $clase = $validator->getMessageBag()->toArray();
            $cadena = "";
            foreach ($clase as $clave => $valor) {
                $cadena =  $cadena . "$valor[0] ";
            }
            $respuesta->result = false;
            $respuesta->mensaje = $cadena;
            $respuesta->data = array('errors' => $validator->getMessageBag()->toArray());
            return response()->json($respuesta);
        }

        $servicio = Servicio::find($id);
        $servicio->nombre = $request->nombre;
        $servicio->descripcion = $request->descripcion;
        $servicio->precio = $request->precio;
        $servicio->precio_oferta = $request->precio_oferta;
        $servicio->tipo_servicio_id = $request->tipo_servicio_id;
        $servicio->estado_oferta = $request->estado_oferta;
        $servicio->update();

        $respuesta->result = true;
        $respuesta->mensaje = "Servicio se actualizó con exito";
        $respuesta->data = "";

        return response()->json($respuesta);
    }

    public function destroy($id)
    {
        $respuesta = new Response();
        $servicio = Servicio::find($id);
        $servicio->estado = 'ANULADO';
        $servicio->update();
        $respuesta->result = true;
        $respuesta->mensaje = "Servicio anulado con exito";
        return response()->json($respuesta);
    }
}
