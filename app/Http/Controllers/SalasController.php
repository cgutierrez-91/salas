<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Http\Request;

class SalasController extends Controller
{
    public function index(){
        if ( session('cuenta')->nivel != 'admin') {
            return view('mensajes.no_autorizado');
        }

        $salas = DB::table('salas')->select()->get();
        return view('salas.index',['salas'=>$salas]);
    }

    public function nueva () {
        if ( session('cuenta')->nivel != 'admin') {
            return view('mensajes.no_autorizado');
        }
        
        return view('salas.nueva');
    }

    public function crear(Request $request){
        if ( session('cuenta')->nivel != 'admin') {
            return view('mensajes.no_autorizado');
        }

        $nombre = $request->input('nombre');
        $capacidad = $request->input('capacidad');
        $aire = $request->input('aire');
        $proyector = $request->input('proyector');
        $otros = $request->input('otros');
        
        $vista = 'mensajes.error';
        $mensaje = '';
        $enfoque = '';
        $reset = '';

        if ( trim($nombre) == '' ) {
            $mensaje = 'El nombre de la sala no puede quedar vacío.';
            $enfoque = 'nombre';
        }
        elseif ( $capacidad < 1 ) {
            $mensaje = 'La capacidad no puede ser menor que 1.';
            $enfoque = 'capacidad';
        }
        else{
            
            $insert = [
                'nombre'    => $nombre,
                'capacidad' => $capacidad,
                'aire'      => $aire == 'on' ? 1 : 0,
                'proyector' => $proyector == 'on' ? 1 : 0,
                'otros'     => $otros ?? ''
            ];
            
            if ( DB::table('salas')->insert($insert) ) {
                $mensaje = 'La sala ha sido registrada correctamente.';
                $vista = 'mensajes.ok';
                $reset = 'salas_form';
            }
            else {
                $mensaje = 'Ocurrió un error al intentar guardar los datos.' . ' Intente nuevamente';
            }
            
        }

        return view($vista, [
            'mensaje' => $mensaje,
            'focus' => $enfoque,
            'reset' => $reset
        ]);

    }


    public function editar ( $id ) {
        if ( session('cuenta')->nivel != 'admin') {
            return view('mensajes.no_autorizado');
        }
        
        $sala = DB::table('salas')->where('sala_id', $id)->first();
        
        if ( is_object($sala) ) {
            return view('salas.editar', ['sala' => $sala]);
        }
        else {
            return view('salas.no_encontrada');
        }
    }

    public function actualizar(Request $request){
        if ( session('cuenta')->nivel != 'admin') {
            return view('mensajes.no_autorizado');
        }
        
        $campo = $request->input('campo');
        $valor = $request->input('valor');
        $checked = $request->input('checked');
        $id = $request->input('id');

        switch ( $campo ){
            case 'proyector': case 'aire':
                DB::table('salas')->where('sala_id', $id)
                    ->update([$campo=>($checked=='true')]);
                return view('mensajes.check_ok', ['mensaje'=>'']);
                break;
            default:
                if ( $campo=='nombre' && strlen(trim($valor))<1 ) {
                    return view('mensajes.check_error', [
                        'mensaje' => 'El nombre de la sala no puede quedar vacío.'
                    ]);
                }
                elseif( $campo=='capacidad' && $valor < 1 ) {
                    return view('mensajes.check_error', [
                        'mensaje' => 'La capacidad debe ser mayor a 0.'
                    ]);
                }
                else {
                    DB::table('salas')->where('sala_id', $id)
                    ->update([$campo=>$valor]);
                    return view('mensajes.check_ok', ['mensaje'=>'Success']);
                }
                break;  
        }
        return '';
    }

    public function eliminar(Request $request) {
        if ( session('cuenta')->nivel != 'admin') {
            return view('mensajes.no_autorizado');
        }
        
        $sala = $request->input('sala');
        DB::table('salas')->where('sala_id', $sala)->delete();
        return view('salas.eliminada', ['sala_id' => $sala]);
    }
}
