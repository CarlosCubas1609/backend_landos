<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use App\Utility\Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function listar()
    {
        $respuesta = new Response();
        $respuesta->result = true;
        /*$users = DB::table('users')
        ->leftjoin('role_user','role_user.user_id','=','users.id')
        ->leftjoin('roles','roles.id','=','role_user.role_id')
        ->select('users.*', 'role_user.role_id as rol_id', 'roles.name as rol')
        ->get();*/
        $users = User::all();
        foreach($users as $user) {
            if($user->roles->count() > 0) {
                $user['rol_id'] = $user->roles[0]->id;
                $user['rol'] = $user->roles[0]->name;
            }
            else {
                $user['rol_id'] = 0;
                $user['rol'] = 0;
            }
        }
        $respuesta->data = $users;
        return response()->json($respuesta);
    }

    public function getUser($id)
    {
        $user = User::find($id);
        $respuesta = new Response();
        $respuesta->result = true;
        $fecha = Carbon::now();
        $respuesta->data = array('user' => $user, 'fecha' => $fecha, 'mes' => mes());
        return response()->json($respuesta);
    }

    public function listarPermisos($id)
    {
        $user = User::find($id);
        $permissions = array();
        if(self::FullAccess($id)) {
            $permissions = Permission::all();
        }
        else {
            foreach ($user->roles as $rol) {
                foreach ($rol->permissions as $perm) {
                    array_push($permissions, $perm);
                }
            }
        }
        $respuesta = new Response();
        $respuesta->result = true;
        $respuesta->data = $permissions;
        return response()->json($respuesta);
    }

    function FullAccess($id)
    {
        $user = User::find($id);
        $fullaccess = false;
        if (count($user->roles) > 0) {
            $cont = 0;
            while ($cont < count($user->roles)) {
                if ($user->roles[$cont]['full_access'] == 'SI') {
                    $fullaccess = true;
                    $cont = count($user->roles);
                }

                $cont = $cont + 1;
            }
        }
        return $fullaccess;
    }

    public function login(Request $request)
    {
        try {
            $respuesta = new Response;
            $data = $request->all();
            $rules = [
                'email' => 'required',
                'password' => 'required'
            ];
            $message = [
                'email.required' => 'Ingrese email.',
                'password.required' => 'Ingrese contraseña',

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
            $sEmail = $request->get('email');
            $sContrasena = $request->get('password');

            $user = User::where('email', $sEmail)->first();
            $mensaje = '';
            $query = User::where('email', '=', $sEmail)->get();
            $result = false;
            $data = '';
            if ($query->count() != 0) {
                if (Auth::guard()->attempt($request->only('email', 'password'), false)) {
                    $result = true;
                    session()->put('user',$user);
                    $data = $user;
                } else {
                    $result = false;
                    $mensaje = 'Contraseña no válida';
                }
            } else {
                $result = false;
                $mensaje = 'No tienes una cuenta';
            }
            $respuesta->result = $result;
            $respuesta->mensaje = $mensaje;
            $respuesta->data = $data;

            return response()->json($respuesta);
        } catch (Exception $e) {
            $respuesta = new Response;
            $respuesta->result = false;
            $respuesta->mensaje = $e->getMessage();
            return response()->json($respuesta);
        }
    }

    public function login2(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');
            $respuesta = new Response;
            if (Auth::attempt($credentials)) {
                // Authentication passed...
                $respuesta->result = true;
            } else {
                $respuesta->result = false;
                $respuesta->mensaje = 'Usuario y/o contraseña incorrecto';
            }

            return response()->json($respuesta);
        } catch (Exception $e) {
            $respuesta = new Response;
            $respuesta->result = false;
            $respuesta->mensaje = $e->getMessage();
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            Log::info($data);
            $respuesta = new Response;
            $rules = [
                'name' => 'required',
                'email' => ['required', Rule::unique('users', 'email')->where(function ($query) {
                    $query->whereIn('estado', ["ACTIVO", "ANULADO"]);
                })],
                'password' => 'required'
            ];
            $message = [
                'name.required' => 'El campo nombre es obligatorio.',
                'email.required' => 'El campo email es obligatorio',
                'email.unique' => 'El campo email debe ser único',
                'password.required' => 'El campo contraseña  es obligatorio'

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

            if ($request->password != $request->confirm_password) {
                DB::rollBack();
                $respuesta->result = false;
                $respuesta->mensaje = 'Contraseñas incorrectas';
                return response()->json($respuesta);
            }

            $user = new User();

            $password = $request->password;
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = Hash::make($password);
            $user->contra = $password;
            $user->save();

            if ($request->get('role')) {
                $user->roles()->sync([(int)$request->get('role')]);
            }

            DB::commit();
            $respuesta->result = true;
            $respuesta->mensaje = "Usuario se registró con exito";
            return response()->json($respuesta);
        } catch (Exception $e) {
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
            Log::info($data);

            $respuesta = new Response;

            if ($id == 1) {
                $respuesta->result = false;
                $respuesta->mensaje = "No puedes editar este usuario";
                return response()->json($respuesta);
            }

            $user = User::find($id);
            $rules = [
                'name' => 'required',
                'email' => ['required', Rule::unique('users', 'email')->where(function ($query) {
                    $query->whereIn('estado', ["ACTIVO", "ANULADO"]);
                })->ignore($id)],
                'password' => 'required',
                'confirm_password' => 'required',
            ];
            $message = [
                'name.required' => 'El campo nombre es obligatorio.',
                'email.required' => 'El campo email es obligatorio',
                'email.unique' => 'El campo email debe ser único',
                'password.required' => 'El campo contraseña  es obligatorio',
                'confirm_password.required' => 'El campo contraseña  es obligatorio',

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


            if ($request->password !== $request->confirm_password) {
                DB::rollBack();
                $respuesta->result = false;
                $respuesta->mensaje = 'Contraseñas incorrectas';
                return response()->json($respuesta);
            }

            $password = $request->password;

            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = Hash::make($password);
            $user->contra = $password;

            $user->update();

            if ($request->get('role')) {
                $user->roles()->sync([(int)$request->get('role')]);
            }

            DB::commit();
            $respuesta->result = true;
            $respuesta->mensaje = "Usuario se actualizo con exito";
            return response()->json($respuesta);
        } catch (Exception $e) {
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
            $respuesta->mensaje = "No puedes eliminar este usuario";
            return response()->json($respuesta);
        }
        $user = User::find($id);
        $user->delete();
        // $rol->estado = 'ANULADA';
        // $rol->update();
        $respuesta->result = true;
        $respuesta->mensaje = "Usuario eliminado con exito";
        return response()->json($respuesta);
    }
}
