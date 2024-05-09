<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Orden;
use App\Models\Vehiculo;
use App\Utility\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class VehiculoController extends Controller
{
    public function listar($id)
    {
        $respuesta = new Response();
        $respuesta->result = true;
        $vehiculos = Vehiculo::where('cliente_id', $id)->where('estado', 'ACTIVO')->get();
        $respuesta->data = $vehiculos;
        return response()->json($respuesta);
    }

    public function vehiculo($placa) {
        $vehiculo = Vehiculo::where('placa', 'like', '%'.$placa.'%')->first();
        $respuesta = new Response();
        if(!empty($vehiculo)) {
            $vehiculo->cliente;
            $respuesta->result = true;
            $respuesta->data = $vehiculo;
        }
        else {
            $respuesta->result = false;
            $respuesta->mensaje = "No hemos encontrado ninguna coincidencia";
        }

        return response()->json($respuesta);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $respuesta = new Response();
            $data = $request->all();
            Log::info($data);
            $rules = [
                'placa' => [
                    'required',
                    Rule::unique('vehiculos', 'placa')->where(function ($query) {
                        $query->whereIn('estado', ["ACTIVO"]);
                    })
                ],
                'color' => 'required',
                'modelo' => 'required',
                'cliente_id' => 'required',

            ];
            $message = [
                'placa.required' => 'El campo placa es obligatorio',
                'placa.unique' => 'El campo placa debe de ser campo único.',
                'color.required' => 'El campo color es obligatorio',
                'modelo.required' => 'El campo modelo es obligatorio',
                'cliente_id.required' => 'El campo cliente es obligatorio',
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
                DB::rollBack();
                return response()->json($respuesta);
            }

            $vehiculo = new Vehiculo();
            $vehiculo->placa = $request->placa;
            $vehiculo->color = $request->color;
            $vehiculo->modelo = $request->modelo;
            $vehiculo->marca = $request->marca;
            $vehiculo->cliente_id = $request->cliente_id;
            $vehiculo->save();

            if (!empty($request->imagen)) {
                if (!file_exists(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'vehiculos'))) {
                    mkdir(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'vehiculos'));
                }
                $image = $request->imagen;
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $image_name = $vehiculo->placa . '-' . $vehiculo->id . '.png';
                Storage::disk('vehiculos')->put($image_name, base64_decode($image));
                $vehiculo->url_foto_placa = '/storage/vehiculos/' . $image_name;
                $vehiculo->update();
            }
            

            $respuesta->result = true;
            $respuesta->mensaje = "Vehículo se registro con exito";
            $respuesta->data = "";
            DB::commit();
            return response()->json($respuesta);
        }
        catch(Exception $e) {
            $respuesta->result = false;
            $respuesta->mensaje = $e->getMessage();
            $respuesta->data = "";
            DB::rollBack();
            return response()->json($respuesta);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $respuesta = new Response();
            $data = $request->all();
            $rules = [
                'placa' => [
                    'required',
                    Rule::unique('vehiculos', 'placa')->where(function ($query) {
                        $query->whereIn('estado', ["ACTIVO"]);
                    })->ignore($id)
                ],
                'color' => 'required',
                'modelo' => 'required',
                'cliente_id' => 'required',

            ];
            $message = [
                'placa.required' => 'El campo placa es obligatorio',
                'placa.unique' => 'El campo placa debe de ser campo único.',
                'color.required' => 'El campo color es obligatorio',
                'modelo.required' => 'El campo modelo es obligatorio',
                'cliente_id.required' => 'El campo cliente es obligatorio',
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
                DB::rollBack();
                return response()->json($respuesta);
            }

            $vehiculo = Vehiculo::find($id);
            $vehiculo->placa = $request->placa;
            $vehiculo->color = $request->color;
            $vehiculo->modelo = $request->modelo;
            $vehiculo->marca = $request->marca;
            $vehiculo->cliente_id = $request->cliente_id;
            $vehiculo->update();
            Orden::query()->where('vehiculo_id', $id)->update(['placa' => $request->placa]);

            if (!empty($request->imagen)) {
                if (!file_exists(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'vehiculos'))) {
                    mkdir(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'vehiculos'));
                }
                $image = $request->imagen;
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $image_name = $vehiculo->placa . '-' . $vehiculo->id . '.png';
                Storage::disk('vehiculos')->put($image_name, base64_decode($image));
                $vehiculo->url_foto_placa = '/storage/vehiculos/' . $image_name;
                $vehiculo->update();
            }

            $respuesta->result = true;
            $respuesta->mensaje = "Vehículo se actualizó con exito";
            $respuesta->data = "";
            DB::commit();
            return response()->json($respuesta);
        } 
        catch (Exception $e) {
            $respuesta->result = false;
            $respuesta->mensaje = $e->getMessage();
            $respuesta->data = "";
            DB::rollBack();
            return response()->json($respuesta);
        }
    }

    public function destroy($id)
    {
        try{
            DB::beginTransaction();
            $respuesta = new Response();
            $ordenes = Orden::where('vehiculo_id', $id)->where('estado','ACTIVO')->get();
            if(count($ordenes) > 0)
            {
                $respuesta->result = false;
                $respuesta->mensaje = "No puedes eliminar este vehiculo porque tiene una orden de servicio activa";
                DB::commit();
                return response()->json($respuesta);
            }
            $vehiculo = Vehiculo::find($id);
            $vehiculo->estado = 'ANULADO';
            if (!empty($vehiculo->url_foto_placa)) {
                $ruta = $vehiculo->url_foto_placa;
                $ruta = str_replace('/storage', 'public', $ruta);
                Log::info("ruta ".$ruta);
                Storage::delete($ruta);
            }
            $vehiculo->update();
            $respuesta->result = true;
            $respuesta->mensaje = "Vehículo anulado con exito";
            DB::commit();
            return response()->json($respuesta);
        }
        catch(Exception $e){
            $respuesta->result = false;
            $respuesta->mensaje = $e->getMessage();
            DB::rollback();
            return response()->json($respuesta);
        }
    }
}
