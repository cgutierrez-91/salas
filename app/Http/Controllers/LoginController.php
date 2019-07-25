<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
//use \Symfony\Component\HttpFoundation\Session\Session;
//use \Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    public function entrar(\Illuminate\Http\Request $request){
        $params = [
            'user' => $request->input('usuario')
        ];

        $usuarios = DB::select('SELECT * FROM cuentas WHERE usuario=:user', $params);

        $usuario = $usuarios[0];
        //$usuario = $usuarios[0] ?? null;
        if(is_object($usuario)){
            $password = $request->input('clave');
            $phash = SHA1( SHA1($password) . $usuario->salt );

            if($usuario->clave == $phash){
                session(['cuenta' => $usuario]);
                return view('login.correcto');
            }
            else{
                return view('login.invalid');
            }
        }
        else{
            return view('login.invalid');
        }
    }

    public function salir(Request $request) {
        //Session::forget('cuenta');
        
        $request->session()->flush();
        return view('login.salida');
    }
}
