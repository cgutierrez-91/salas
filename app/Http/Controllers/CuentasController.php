<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Http\Request;

class CuentasController extends Controller
{
    public function index() {
        
        if ( session('cuenta')->nivel != 'admin') {
            return view('mensajes.no_autorizado');
        }
        
        $cuentas = DB::select('SELECT * FROM cuentas');
        $params = [
            'cuentas'   => $cuentas
        ];
        return view('cuentas.index', $params);
    }

    public function nueva() {
        if ( session('cuenta')->nivel != 'admin') {
            return view('mensajes.no_autorizado');
        }
        
        return view('cuentas.nueva');
    }

    public function editar($id){
        if ( session('cuenta')->nivel != 'admin') {
            return view('mensajes.no_autorizado');
        }

        $params = [
            'id'    => $id
        ];

        $usuario = DB::select('SELECT * FROM cuentas WHERE cuenta_id=:id', $params);

        if ( $usuario ) {
            return view('cuentas.editar', ['usuario' => $usuario[0]]);
        }
        else{
            return view('cuentas.no_existe');
        }
    }

    public function crear(Request $request){
        if ( session('cuenta')->nivel != 'admin') {
            return view('mensajes.no_autorizado');
        }

        $usuario = strip_tags( $request->input('usuario') );
        $email = strip_tags( $request->input('email') );
        $clave = strip_tags( $request->input('clave') );
        $confirmar = strip_tags( $request->input('confirmar') );
        $nombre = trim(strip_tags( $request->input('nombre') ));

        $eu_params = ['x' => $usuario];
        $existe_usuario = Db::select('SELECT * FROM cuentas WHERE usuario=:x', $eu_params);

        $ee_params = ['x' => $email];
        $existe_email = Db::select('SELECT * FROM cuentas WHERE email=:x', $ee_params);

        $mensaje = '';
        $focus='';
        $reset = '';
        $vista = 'mensajes.error';

        if ( !preg_match('/^[a-z0-9_]{6,20}$/', $usuario) ) {
            $mensaje = 'Usuario no válido. Sólo se permiten minúsculas y números.' . ' Mínimo 6 / Máximo 20 caracteres.';
            $focus = 'usuario';
        }
        elseif(isset( $existe_usuario[0]) ){
            $mensaje = 'El usuario <strong>' . $usuario . '</strong> ya está en uso.';
            $focus = 'usuario';
        }
        elseif ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            $mensaje = 'Correo electrónico no válido';
            $focus = 'email';
        }
        elseif(isset( $existe_email[0]) ){
            $mensaje = 'El correo electrónico <strong>' . $email . '</strong> ya está en uso.';
            $focus = 'email';
        }
        elseif ( strlen($clave) < 6 ) {
            $mensaje = 'La contraseña debe contener por lo menos 6 carácteres.';
            $focus = 'clave';
        }
        elseif ( $clave != $confirmar ) {
            $mensaje = 'Las contraseñas no coinciden.';
            $focus = 'clave';
        }
        elseif ( strlen($nombre) < 5 ) {
            $mensaje = 'El nombre completo debe contener por lo menos 5 carácteres';
            $focus = 'nombre';
        }
        else{
            
            $salt = sha1(microtime());
            $clave_hash = sha1( sha1($clave) . $salt );
            $datos_insert = [
                'usuario'   => $usuario,
                'email'     => $email,
                'salt'      => $salt,
                'nombre'    => $nombre,
                'clave'     => $clave_hash,
                'nivel'     => 'usuario'
            ];
            
            $nuevo_id = DB::table('cuentas')->insertGetId($datos_insert);
            
            if ( $nuevo_id > 0 ) {
                $mensaje = 'La cuenta fue creada satisfactoriamente.';
                $vista = 'mensajes.ok';
                $focus = 'usuario';
                $reset = 'cuenta_nueva_form';
            }
            else {
                $mensaje = 'Ocurrió un error inesperado. Intente nuevamente.';
            }
        }

        $params = [
            'mensaje'   => $mensaje,
            'focus'     => $focus,
            'reset'     => $reset
        ];
        return view($vista, $params);
    }

    public function disponible(Request $request) {
        if ( session('cuenta')->nivel != 'admin') {
            return view('mensajes.no_autorizado');
        }
        
        $params=[
            'usuario'=>$request->input('usuario'),
            'email'=>$request->input('usuario')
        ];
        $cuenta = DB::select('SELECT * FROM cuentas WHERE usuario=:usuario' . ' OR email=:email', $params);
        
        if ( !isset($cuenta[0]) ) {
            return view('cuentas.disponible');
        }
        else{
            return view('cuentas.nodisponible');
        }
    }

    public function eliminar(Request $request) {
        if ( session('cuenta')->nivel != 'admin') {
            return view('mensajes.no_autorizado');
        }
        
        $cuenta_id = $request->input('cuenta');
        
        $cuenta = DB::table('cuentas')->where('cuenta_id', $cuenta_id)->first();

        if ( is_object( $cuenta ) ) {
            if ( $cuenta->nivel != 'admin' ) {
                DB::table('cuentas')->where('cuenta_id', $cuenta_id)->delete();
                return view('cuentas.eliminada', ['cuenta_id'=>$cuenta_id]);
            }
            else{
                return view('cuentas.eliminar_admin');
            }
        }
    }

    public function actualizar(Request $request){
        if ( session('cuenta')->nivel != 'admin') {
            return view('mensajes.no_autorizado');
        }

        $campo = strip_tags( $request->input('campo') );
        $valor = strip_tags( $request->input('valor') );
        $id = $request->input('id');

        if ( $campo == 'usuario' ){
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
            return view('mensajes.check_ok', ['mensaje'=>'Hecho']);
        }
        else{
            return view('mensajes.check_alerta', ['mensaje'=>'Sin cambios']);
        }
        
    }

    public function password($id) {
        if ( session('cuenta')->nivel != 'admin') {
            return view('mensajes.no_autorizado');
        }
        
        $usuario = DB::table('cuentas')->where('cuenta_id', $id)->first();
        if ( is_object($usuario) ) {
            return view('cuentas.password', [
                'cuenta' => $usuario
            ]);
        }
        else {
            return view('Not Found');
        }
    }

    public function password_actualizar(Request $request){
        if ( session('cuenta')->nivel != 'admin') {
            return view('mensajes.no_autorizado');
        }
        
        $cuenta_id = $request->input('cuenta_id');
        
        $clave = strip_tags( $request->input('clave') );
        $confirmar = strip_tags( $request->input('confirmar') );
        $salt = sha1(microtime());
        $clave_hash = sha1( sha1($clave) . $salt );

        if ( strlen($clave) < 6 ) {
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
                return view('mensajes.ok', [
                    'mensaje' => 'Actualizada'
                ]);
            }
            else{
                $mensaje = 'No se hicieron cambios';
            }
        }
        return view('mensajes.error', ['mensaje'=>$mensaje]);
    }
    
}
