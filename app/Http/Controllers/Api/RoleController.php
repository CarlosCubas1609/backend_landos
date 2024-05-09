<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Utility\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function listar()
    {
        $respuesta = new Response();
        $respuesta->result = true;
        $roles = Role::all();
        $respuesta->data = $roles;
        return response()->json($respuesta);
    }
    

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            Log::info($data);
            Log::info(json_decode($request->get('permission')));
            $respuesta = new Response;
            $rules = [
                'name' => 'required|max:50|unique:roles,name',
                'slug' => 'required|max:50|unique:roles,slug',
                'full_access' => 'required|in:SI,NO',
            ];

            $message = [
                'name.required' => 'El campo nombre es obligatorio.',
                'name.unique' => 'El campo nombre debe ser único.',
                'slug.required' => 'El campo slug es obligatorio.',
                'slug.unique' => 'El campo slug debe ser único.',
                'full_access.required' => 'El campo slug debe ser único.',
                'full_access.in' => 'El campo acceso total acepta SI/NO.',
            ];

            $validator =  Validator::make($data, $rules, $message);

            if ($validator->fails()) {
                $clase = $validator->getMessageBag()->toArray();
                $cadena = "";
                foreach ($clase as $clave => $valor) {
                    $cadena =  $cadena . "$valor[0] ";
                }
                DB::rollBack();
                $respuesta->result = false;
                $respuesta->mensaje = $cadena;
                $respuesta->data = array('errors' => $validator->getMessageBag()->toArray());
                return response()->json($respuesta);
            }
            $role = Role::create($request->all());
            $role->name = strtoupper($request->get('name'));
            $role->description = strtoupper($request->get('description'));
            $role->slug = strtoupper($request->get('slug'));
            $role->update();

            $arr = json_decode($request->get('permission'));
            if (count($arr) > 0) {
                $role->permissions()->sync($arr);
            }

            DB::commit();
            $respuesta->result = true;
            $respuesta->mensaje = "Usuario se registró con exito";
            return response()->json($respuesta);
        }
        catch(Exception $e) {
            DB::rollBack();
            $respuesta = new Response;
            $respuesta->result = false;
            $respuesta->mensaje = $e->getMessage();
            return response()->json($respuesta);
        }       

    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $respuesta = new Response;
            if ($id == 1) {
                $respuesta->result = false;
                $respuesta->mensaje = "No puedes editar este rol";
                return response()->json($respuesta);
            }
            Log::info($data);
            Log::info(json_decode($request->get('permission')));
            if ($id == 1) {
                $respuesta->result = false;
                $respuesta->mensaje = "No puedes editar este usuario";
                return response()->json($respuesta);
            }

            $role = Role::find($id);
            $rules = [
                'name' => 'required|max:50|unique:roles,name,' . $role->id,
                'slug' => 'required|max:50|unique:roles,slug,' . $role->id,
                'full_access' => 'required|in:SI,NO',
            ];

            $message = [
                'name.required' => 'El campo nombre es obligatorio.',
                'name.unique' => 'El campo nombre debe ser único.',
                'slug.required' => 'El campo slug es obligatorio.',
                'slug.unique' => 'El campo slug debe ser único.',
                'full_access' => 'El campo full-access acepta SI/NO.',
            ];

            $validator =  Validator::make($data, $rules, $message);

            if ($validator->fails()) {
                $clase = $validator->getMessageBag()->toArray();
                $cadena = "";
                foreach ($clase as $clave => $valor) {
                    $cadena =  $cadena . "$valor[0] ";
                }
                DB::rollBack();
                $respuesta->result = false;
                $respuesta->mensaje = $cadena;
                $respuesta->data = array('errors' => $validator->getMessageBag()->toArray());
                return response()->json($respuesta);
            }

            $role->update($request->all());
            $role->name = strtoupper($request->get('name'));
            $role->description = strtoupper($request->get('description'));
            $role->slug = strtoupper($request->get('slug'));
            $role->update();
            $arr = json_decode($request->get('permission'));
            if (count($arr) > 0) {
                $role->permissions()->sync($arr);
            } else {
                $role->permissions()->sync([]);
            }

            DB::commit();
            $respuesta->result = true;
            $respuesta->mensaje = "Usuario se actualizó con exito";
            return response()->json($respuesta);
        }
        catch(Exception $e) {
            DB::rollBack();
            $respuesta = new Response;
            $respuesta->result = false;
            $respuesta->mensaje = $e->getMessage();
            return response()->json($respuesta);
        }     
    }

    public function destroy($id)
    {
        $respuesta = new Response();
        if ($id == 1) {
            $respuesta->result = true;
            $respuesta->mensaje = "No puedes eliminar este rol";
            return response()->json($respuesta);
        }
        $rol = Role::find($id);
        $rol->delete();
        // $rol->estado = 'ANULADA';
        // $rol->update();
        $respuesta->result = true;
        $respuesta->mensaje = "Rol elimninado con exito";
        return response()->json($respuesta);
    }
}
