<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function perfil() {
        return view('perfil.mi_perfil', ['usuario'=>session('cuenta')]);
    }
    
    public function clave() {
        return view('perfil.clave');
    }

    public function actualizar(Request $request){
        $campo = strip_tags( $request->input('campo') );
        $valor = strip_tags( $request->input('valor') );
        $id = session('cuenta')->cuenta_id;

        if ( $campo == 'usuario' ) {
            $usuario = DB::table('cuentas')
                    ->where('usuario', $valor)
                    ->where('cuenta_id', '<>', $id)->first();

            if ( is_object($usuario) ) {
                $params = [
                    'mensaje' => 'El usuario ya existe.'
                ];
                return view('mensajes.check_error', $params);
            }
            
            if ( !preg_match('/^[a-z0-9_]{6,20}$/', $valor) ) {
                $params = [
                    'mensaje' => 'Usuario no válido. Mín. 6 / Máx. 20 carácteres'
                ];
                return view('mensajes.check_error', $params);
            }
        }

        if ( $campo == 'email' ) {
            $usuario = DB::table('cuentas')
                    ->where('email', $valor)
                    ->where('cuenta_id', '<>', $id)->first();

            if ( is_object($usuario) ) {
                $params = [
                    'mensaje' => 'La cuenta de correo ya está en uso.'
                ];
                return view('mensajes.check_error', $params);
            }
            
            if ( !filter_var($valor, FILTER_VALIDATE_EMAIL) ) {
                $params = [
                    'mensaje' => 'Correo no válido.'
                ];
                return view('mensajes.check_error', $params);
            }
        }

        if ( $campo == 'nombre' && strlen( trim($valor) ) < 5 ) {
            $params = [
                'mensaje' => 'El nombre debe contener por lo menos 5 carácteres.'
            ];
            return view('mensajes.check_error', $params);
        }

        $afectado = DB::table('cuentas')->where('cuenta_id', $id)
                ->update([$campo => $valor]);

        if ( $afectado > 0 ) {
            $usuario = DB::table('cuentas')->where('cuenta_id', $id)
            ->select('*')->first();
            session(['cuenta' => $usuario]);
            return view('mensajes.check_ok', ['mensaje'=>'Hecho']);
        }
        else{
            return view('mensajes.check_alerta', ['mensaje'=>'Sin cambios']);
        }
    }

    public function clave_actualizar(Request $request){
        $cuenta_id = session('cuenta')->cuenta_id;
        
        $clave = strip_tags( $request->input('clave') );
        $confirmar = strip_tags( $request->input('confirmar') );
        $salt = sha1(microtime());
        $clave_hash = sha1( sha1($clave) . $salt );
        $actual = strip_tags( $request->input('actual') );
        $clave_actual = sha1( sha1($actual) . session('cuenta')->salt );

        if ( $clave_actual != session('cuenta')->clave ) {
            $mensaje = 'La contraseña actual es inválida.';
            $focus = 'actual';
        }
        elseif ( strlen($clave) < 6 ) {
            $mensaje = 'La contraseña debe contener por lo menos 6 carácteres.';
            $focus = 'clave';
        }
        elseif ( $clave != $confirmar ) {
            $mensaje = 'Las contraseñas no coinciden.';
            $focus = 'clave';
        }
        else {
            $actualizada = DB::table('cuentas')->where('cuenta_id', $cuenta_id)
                ->update([
                    'salt'  => $salt,
                    'clave' => $clave_hash
                ]);
            
            if ( $actualizada > 0 ) {
                $usuario = DB::table('cuentas')->where('cuenta_id', $cuenta_id)
                ->select('*')->first();
                session(['cuenta' => $usuario]);
                
                return view('mensajes.ok', [
                    'mensaje' => 'Actualizada'
                    . '<script>$("form.card.xhr").trigger("reset");</script>'
                ]);
            }
            else{
                $mensaje = 'No se hicieron cambios';
            }
        }
        return view('mensajes.error', ['mensaje'=>$mensaje]);
    }
}
