<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Orden;
use App\Utility\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function listar() {
        $respuesta = new Response();
        $respuesta->result = true;
        $clientes = Cliente::where('estado', 'ACTIVO')->get();
        $respuesta->data = $clientes;
        return response()->json($respuesta);
    }

    public function store(Request $request) {
        $respuesta = new Response();
        $data = $request->all();
        Log::info($data);
        $rules = [
            'tipo_doc' => 'required',
            'documento' => [
                'required',
                Rule::unique('clientes', 'documento')->where(function ($query) {
                    $query->whereIn('estado', ["ACTIVO"]);
                })
            ],
            'nombres' => 'required',
            'apellidos' => 'required',
            'celular' => 'required',
            'genero_id' => 'required',
            'direccion' => 'nullable',

        ];
        $message = [
            'tipo_doc.required' => 'El campo tipo documento es obligatorio',
            'documento.required' => 'El campo documento es obligatorio',
            'documento.unique' => 'El campo documento debe de ser campo único.',
            'nombres.required' => 'El campo nombres es obligatorio',
            'apellidos.required' => 'El campo apellidos es obligatorio',
            'celular.required' => 'El campo celular es obligatorio',
            'genero_id.required' => 'El campo genero es obligatorio',
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

        $cliente = new Cliente();
        $cliente->tipo_documento_id = $request->tipo_doc;
        $cliente->documento = $request->documento;
        $cliente->nombres = $request->nombres;
        $cliente->apellidos = $request->apellidos;
        $cliente->celular = $request->celular;
        $cliente->email = $request->email;
        $cliente->direccion = $request->direccion;
        $cliente->fecha_nacimiento = $request->fecha_nacimiento;
        $cliente->genero_id = $request->genero_id;
        $cliente->save();

        $respuesta->result = true;
        $respuesta->mensaje = "Cliente se registro con exito";
        $respuesta->data = "";
        
        return response()->json($respuesta);
    }

    public function update(Request $request, $id)
    {
        $respuesta = new Response();
        $data = $request->all();
        Log::info($data);
        $rules = [
            'tipo_doc' => 'required',
            'documento' => [
                'required',
                Rule::unique('clientes', 'documento')->where(function ($query) {
                    $query->whereIn('estado', ["ACTIVO"]);
                })->ignore($id)
            ],
            'nombres' => 'required',
            'apellidos' => 'required',
            'celular' => 'required',
            'genero_id' => 'required',
            'direccion' => 'nullable',

        ];
        $message = [
            'tipo_doc.required' => 'El campo tipo documento es obligatorio',
            'documento.required' => 'El campo documento es obligatorio',
            'documento.unique' => 'El campo documento debe de ser campo único.',
            'nombres.required' => 'El campo nombres es obligatorio',
            'apellidos.required' => 'El campo apellidos es obligatorio',
            'celular.required' => 'El campo celular es obligatorio',
            'genero_id.required' => 'El campo genero es obligatorio',
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

        $cliente = Cliente::find($id);
        $cliente->tipo_documento_id = $request->tipo_doc;
        $cliente->documento = $request->documento;
        $cliente->nombres = $request->nombres;
        $cliente->apellidos = $request->apellidos;
        $cliente->celular = $request->celular;
        $cliente->email = $request->email;
        $cliente->direccion = $request->direccion;
        $cliente->fecha_nacimiento = $request->fecha_nacimiento;
        $cliente->genero_id = $request->genero_id;
        $cliente->update();

        $respuesta->result = true;
        $respuesta->mensaje = "Cliente se actualizó con exito";
        $respuesta->data = "";

        return response()->json($respuesta);
    }

    public function destroy($id) {
        $respuesta = new Response();
        $ordenes = Orden::where('cliente_id', $id)->where('estado', 'ACTIVO')->get();
        if (count($ordenes) > 0) {
            $respuesta->result = false;
            $respuesta->mensaje = "No puedes eliminar este cliente porque tiene una orden de servicio activa";
            return response()->json($respuesta);
        }
        $cliente = Cliente::find($id);
        $cliente->estado = 'ANULADO';
        $cliente->update();
        $respuesta->result = true;
        $respuesta->mensaje = "Cliente anulado con exito";
        return response()->json($respuesta);
    }
}
