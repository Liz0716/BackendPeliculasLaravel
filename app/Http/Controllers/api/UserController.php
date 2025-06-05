<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/'
            ],
            'rol' => 'required'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->rol = $request->rol;

        $user->save();

        $correo = new CorreoController();
        $correo->sendEmail($user->id);

        return response()->json([
            'estatus' => 1,
            'mensaje' => 'Registrado'
        ]);
    }

    public function password(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8'
        ]);

        User::where("id", $id)->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'estatus' => 1,
            'mensaje' => 'Actualizado'
        ]);
    }

    public function update(Request $request, $id)
    {
        User::where("id", $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'rol' => $request->rol
        ]);

        return response()->json([
            'estatus' => 1,
            'mensaje' => 'Actualizado'
        ]);
    }

    public function delete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['estatus' => false]);
        }

        $user->update(['eliminado' => 1]);

        return response()->json(['estatus' => true]);
    }

    public function getUsuarios(Request $request)
    {
        $sort = $request->input('sortOrder', 1) == 1 ? 'asc' : 'desc';
        $sortfield = $request->input('sortField', 'name');
        $limit = $request->input('rows', 100);
        $offset = $request->input('first', 0);


        $usuariosQuery = User::where('eliminado', 0);

        if (!empty($request->input('globalFilter'))) {
            $filtro = '%' . $request->input('globalFilter') . '%';
            $usuariosQuery->where(function ($query) use ($filtro) {
                $query->where('name', 'like', $filtro)
                    ->orWhere('email', 'like', $filtro);
            });
        }

        $total = $usuariosQuery->count();

        $usuarios = $usuariosQuery
            ->orderBy($sortfield, $sort)
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->toArray();

        return response()->json([
            'data' => $usuarios,
            'totalRecords' => $total
        ]);
    }


    public function getUsuariosById($id)
    {
        $usuario = User::find($id);

        if ($usuario) {
            return response()->json([
                'estatus' => 1,
                'mensaje' => 'Usuario encontrado',
                'usuario' => $usuario
            ]);
        } else {
            return response()->json([
                'estatus' => 0,
                'mensaje' => 'Usuario no encontrado'
            ], 404);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', '=', $request->email)->first();
        if (isset($user->id)) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'estatus' => 1,
                    'mensaje' => 'Usuario correcto',
                    'name' => $user->name,
                    'rol' => $user->rol,
                    'access_token' => $token
                ]);
            } else {
                return response()->json([
                    'estatus' => 0,
                    'mensaje' => 'Contraseña incorrecta'
                ], 404);
            }
        } else {
            return response()->json([
                'estatus' => 0,
                'mensaje' => 'Usuario inexistente'
            ], 404);
        }
    }

    public function userProfile()
    {
        return response()->json([
            'estatus' => 1,
            'mensaje' => 'Perfil Usuario',
            'name' => auth()->user()->name,
            'rol' => auth()->user()->rol
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'estatus' => 1,
            'mensaje' => 'Cierre de Sesión'
        ]);
    }
}
