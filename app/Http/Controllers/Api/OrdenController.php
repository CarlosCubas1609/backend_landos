<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Orden;
use App\Models\Servicio;
use App\Models\Vehiculo;
use App\Utility\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OrdenController extends Controller
{
    public function listar()
    {
        $respuesta = new Response();
        $respuesta->result = true;
        $ordenes = DB::table('ordens')
        ->join('vehiculos','ordens.vehiculo_id', '=', 'vehiculos.id')
        ->select('ordens.*', 'vehiculos.url_foto_placa as url_image')
        ->where('ordens.estado','!=','ANULADO')
        ->orderBy('ordens.created_at','desc')
        ->get();
        $respuesta->data = $ordenes;
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
                'cliente_id' => 'required',
                'telefono' => 'nullable',
                'vehiculo_id' => 'required',
                'placa' => 'required',
                'servicio_id' => 'required',
                'total' => 'required',

            ];
            $message = [
                'cliente_id.unique' => 'El campo cliente es obligatorio',
                'vehiculo_id.required' => 'El campo vehiculo es obligatorio',
                'placa.required' => 'El campo placa es obligatorio',
                'servicio_id.required' => 'El campo servicio es obligatorio',
                'total.required' => 'El campo total es obligatorio',
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

            $cliente = Cliente::find($request->cliente);
            $vehiculo = Vehiculo::find($request->vehiculo_id);
            $servicio = Servicio::find($request->servicio_id);

            $orden = new Orden();
            $orden->cliente = $cliente ? ($cliente->nombres . ' ' . $cliente->apellidos) : "-";
            $orden->cliente_id = $request->cliente_id;
            $orden->telefono = $request->telefono;
            $orden->vehiculo = $vehiculo ? ($vehiculo->placa . ' - ' . $vehiculo->modelo) : "-";
            $orden->vehiculo_id = $request->vehiculo;
            $orden->placa = $request->placa;
            $orden->servicio = $servicio ? ($servicio->nombre . ' ' . ($servicio->precio - ($request->descuento != '' ? $request->descuento : 0))) : "-";
            $orden->servicio_id = $request->servicio_id;
            $orden->total = $request->total - ($request->descuento != '' ? $request->descuento : 0);
            $orden->descuento = $request->descuento != '' ? $request->descuento : 0;
            $orden->user_id = $request->user_id;
            $orden->save();

            $servicio = $orden->servicio_model;

            // if ($orden->cliente_model->email) {
            //     Mail::send('mail.orden', ['orden' => $orden, 'servicio' => $servicio], function ($mail) use ($orden) {
            //         $mail->to($orden->cliente_model->email);
            //         $mail->subject('Landos (tu orden de servicio)');
            //         $mail->from('kidaddy20@gmail.com', 'LANDOS');
            //     });
            // }

            $respuesta->result = true;
            $respuesta->mensaje = "La orden se registro con exito";
            $respuesta->data = "";
            DB::commit();
            return response()->json($respuesta);
        }
        catch(Exception $e)
        {
            $respuesta->result = false;
            $respuesta->mensaje = $e->getMessage();
            $respuesta->data = "";
            DB::rollBack();
            return response()->json($respuesta);
        }
    }

    public function update(Request $request, $id)
    {
        try{
            $respuesta = new Response();
            $data = $request->all();
            Log::info($data);
            $rules = [
                'cliente_id' => 'required',
                'telefono' => 'nullable',
                'vehiculo_id' => 'required',
                'placa' => 'required',
                'servicio_id' => 'required',
                'total' => 'required',

            ];
            $message = [
                'cliente_id.unique' => 'El campo cliente es obligatorio',
                'vehiculo_id.required' => 'El campo vehiculo es obligatorio',
                'placa.required' => 'El campo placa es obligatorio',
                'servicio_id.required' => 'El campo servicio es obligatorio',
                'total.required' => 'El campo total es obligatorio',
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

            $cliente = Cliente::find($request->cliente);
            $vehiculo = Vehiculo::find($request->vehiculo_id);
            $servicio = Servicio::find($request->servicio_id);

            $orden = Orden::find($id);
            $orden->cliente = $cliente ? ($cliente->nombres . ' ' . $cliente->apellidos) : "-";
            $orden->cliente_id = $request->cliente_id;
            $orden->telefono = $request->telefono;
            $orden->vehiculo = $vehiculo ? ($vehiculo->placa . ' - ' . $vehiculo->modelo) : "-";
            $orden->vehiculo_id = $request->vehiculo;
            $orden->placa = $request->placa;
            $orden->servicio = $servicio ? ($servicio->nombre . ' ' . $servicio->precio) : "-";
            $orden->servicio_id = $request->servicio_id;
            $orden->total = $request->total;
            $orden->descuento = $request->descuento != '' ? $request->descuento : 0;
            $orden->update();


            $respuesta->result = true;
            $respuesta->mensaje = "La orden se actualizó con exito";
            $respuesta->data = "";
            DB::commit();
            return response()->json($respuesta);
        }
        catch(Exception $e)
        {
            $respuesta->result = false;
            $respuesta->mensaje = "Ocurrió un error vuelva a intentarlo";
            $respuesta->data = "";
            DB::rollBack();
            return response()->json($respuesta);
        }
    }

    public function destroy($id)
    {
        $respuesta = new Response();
        $orden = Orden::find($id);
        $orden->estado = 'ANULADO';
        $orden->update();
        $respuesta->result = true;
        $respuesta->mensaje = "Orden anulada con exito";
        return response()->json($respuesta);
    }

    public function storePago(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $respuesta = new Response();
            $rules = [
                'tipo_pago_id' => 'required',
                'efectivo' => 'required',
                'importe' => 'required',

            ];

            $message = [
                'tipo_pago_id.required' => 'El campo modo de pago es obligatorio.',
                'importe.required' => 'El campo importe es obligatorio.',
                'efectivo.required' => 'El campo efectivo es obligatorio.'
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
                return response()->json($respuesta);

            }

            $venta = Orden::find($request->id);

            $venta->tipo_pago_id = $request->get('tipo_pago_id');
            $venta->importe = $request->get('importe');
            $venta->efectivo = $request->get('efectivo');
            $venta->estado_pago = 'PAGADA';
            $venta->update();

            DB::commit();

            $respuesta->result = true;
            $respuesta->mensaje = "El pago se realizó con exito";
            return response()->json($respuesta);
        } catch (Exception $e) {
            DB::rollBack();
            $respuesta->result = false;
            $respuesta->mensaje = "Ocurrió un error al intentar realizar el pago";
            return response()->json($respuesta);
        }
    }

    public function updatePago(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();

            $rules = [
                'tipo_pago_id' => 'required',
                'efectivo' => 'required',
                'importe' => 'required',

            ];

            $message = [
                'tipo_pago_id.required' => 'El campo modo de pago es obligatorio.',
                'importe.required' => 'El campo importe es obligatorio.',
                'efectivo.required' => 'El campo efectivo es obligatorio.'
            ];

            $validator =  Validator::make($data, $rules, $message);

            if ($validator->fails()) {
                $clase = $validator->getMessageBag()->toArray();
                $cadena = "";
                foreach ($clase as $clave => $valor) {
                    $cadena =  $cadena . "$valor[0] ";
                }
                DB::rollBack();
            }

            $documento = Orden::find($request->id);

            $documento->tipo_pago_id = $request->get('tipo_pago_id');
            $documento->importe = $request->get('importe');
            $documento->efectivo = $request->get('efectivo');
            $documento->estado_pago = 'PAGADA';
            $documento->banco_empresa_id = $request->get('cuenta_id');
            $documento->update();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function deleteImage($ruta_pago)
    {
        try {
            $sRutaImagenActual = str_replace('/storage', 'public', $ruta_pago);
            $sNombreImagenActual = str_replace('public/', '', $sRutaImagenActual);
            Storage::disk('public')->delete($sNombreImagenActual);
            return array('success' => true, 'mensaje' => 'Imagen eliminada');
        } catch (Exception $e) {
            return array('success' => false, 'mensaje' => $e->getMessage());
        }
    }
}
