<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Utility\Response;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function listar($id)
    {
        $respuesta = new Response();
        $respuesta->result = true;
        $permissions = Permission::all();
        $rol = Role::find($id);
        foreach($permissions as $permission) {
            $permission['state'] = '0';
            if($rol) {
                $valida = false;
                for($i = 0; $i < $rol->permissions->count(); $i++) {
                    if($permission->id == $rol->permissions[$i]->id) {
                        $valida = true;
                        $i = $rol->permissions->count();
                    }
                }

                if ($valida) {
                    $permission['state'] = '1';
                }
            }
        }
        $respuesta->data = $permissions;
        return response()->json($respuesta);
    }
}
