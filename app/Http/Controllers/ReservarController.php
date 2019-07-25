<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ReservarController extends Controller
{

    private function notificar($email, $nombre, $asunto, $mensaje){
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'notificacionescampestre@gmail.com';                 // SMTP username
            $mail->Password = 'fXZp4iSOHI0c3Er';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to
            $mail->CharSet = 'UTF-8';

            //Recipients
            $mail->setFrom('notificacionescampestre@gmail.com', 'Notificaciones Campestre');
            $mail->addAddress($email, $nombre);     // Add a recipient
            /*$mail->addAddress('ellen@example.com');               // Name is optional
            $mail->addReplyTo('info@example.com', 'Information');
            $mail->addCC('cc@example.com');
            $mail->addBCC('bcc@example.com');*/

            //Attachments
            /*$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $asunto;
            $mail->Body    = $mensaje;
            $mail->AltBody = strip_tags($mensaje);

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    private function notificacion($reservacion) {
        $reservacion_info = DB::table('reservaciones')
        ->leftJoin('cuentas', 'reservaciones.cuenta', '=', 'cuentas.cuenta_id')
        ->leftJoin('salas', 'reservaciones.sala', '=', 'salas.sala_id')
        ->orderBy('reservacion_id', 'DESC')
        ->where('reservacion_id', $reservacion)
        ->select('reservaciones.*', 'cuentas.email', 'cuentas.usuario', 'cuentas.nombre AS solicitante', 'salas.nombre AS sala_nombre')
        ->first();
        
        $params = [
            'sala'              => $reservacion_info->sala_nombre,
            'fecha'             => $reservacion_info->f_uso_desde,
            'fecha_solicitud'   => $reservacion_info->f_reserva,
            'url'               => (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'],
            'estado'            => $reservacion_info->estado,
			'observacion'       => $reservacion_info->observaciones //OJO ACA
        ];

        $mensaje = view('mensajes.respuesta', $params);
        $asunto = 'Respuesta a solicitud de reserva de sala';

        $this->notificar($reservacion_info->email, $reservacion_info->solicitante, $asunto, $mensaje);

    }


    public function index(Request $request){
        $limite = 10;
        $total = DB::table('reservaciones')->select('*');
        $actual = $request->input('actual') ?? 1;
        
        $reservaciones = DB::table('reservaciones')
        ->leftJoin('cuentas', 'reservaciones.cuenta', '=', 'cuentas.cuenta_id')
        ->leftJoin('salas', 'reservaciones.sala', '=', 'salas.sala_id')
        ->orderBy('reservacion_id', 'DESC')
        ->limit($limite)
        ->offset(($actual-1)*$limite)
        ->select('reservaciones.*', 'cuentas.usuario', 'cuentas.nombre AS solicitante', 'salas.nombre AS sala_nombre');
        
        $nombre_solicitante = '';
        $nombre_sala = '';
        
        if ( $request->input('filtro-sala') > 0 ) {
            $reservaciones=$reservaciones->where('sala', $request->input('filtro-sala'));
            $total=$total->where('sala', $request->input('filtro-sala'));
            
            $xsala = DB::table('salas')
            ->where('sala_id', $request->input('filtro-sala'))
            ->select('*')->first();
            if ( is_object($xsala) ) {
                $nombre_sala = $xsala->nombre;
            }
        }
        
        if ( $request->input('filtro-solicitante') > 0 ) {
            $reservaciones=$reservaciones->where('cuenta', $request->input('filtro-solicitante'));
            $total=$total->where('cuenta', $request->input('filtro-solicitante'));
            
            $xsolicitante = DB::table('cuentas')
            ->where('cuenta_id', $request->input('filtro-solicitante') )
            ->select('*')->first();
            
            if ( is_object($xsolicitante) ) {
                $nombre_solicitante = $xsolicitante->nombre;
            }
        }
        
        if ( $request->input('filtro-estado') != "TODOS" && $request->input('filtro-estado') != "" ) {
            $reservaciones=$reservaciones->where('estado', $request->input('filtro-estado'));
            $total=$total->where('estado', $request->input('filtro-estado'));
        }
        
        if ( session('cuenta')->nivel != 'admin' ) {
            $reservaciones = $reservaciones->where('cuenta', session('cuenta')->cuenta_id);
            $total=$total->where('cuenta', session('cuenta')->cuenta_id);
        }
        
        $reservaciones = $reservaciones->get();
        $total = $total->count();
        
        $salas = DB::table('salas')->select('*')->orderBy('nombre')->get();
        $cuentas = DB::table('cuentas')->select('*')->orderBy('nombre')->get();
        
        $params = [
            'reservaciones'     => $reservaciones,
            'total'             => $total,
            'limite'            => $limite,
            'actual'            => $actual,
            'salas'             => $salas,
            'sala_actual'       => $request->input('filtro-sala'),
            'solicitante_actual'=> $request->input('filtro-solicitante'),
            'estado_actual'     => $request->input('filtro-estado'),
            'cuentas'           => $cuentas,
            'nombre_sala'       => $nombre_sala,
            'nombre_solicitante'=> $nombre_solicitante
        ];
        return view('reservar.index', $params);
    }

    public function nueva($anio = '', $mes='', $sala='') {
        $anio = $anio=='' ? date('Y') : $anio;
        $mes = $mes=='' ? date('m') : $mes;
        
        return $this->calendario($mes, $anio, $sala);
    }

    public function reservar(Request $request) {
        $fecha = $request->input('fecha');
        $sala = $request->input('sala');
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');
        
        if ( $desde >= $hasta ) {
            return view('mensajes.error', [
                'mensaje' => 'La hora de inicio debe ser menor a la hora de finalización'
            ]);
        }
        
        $params = [
            'sala'          => $sala,
            'cuenta'        => session('cuenta')->cuenta_id,
            'f_reserva'     => date('Y-m-d H:i:s'),
            'f_uso_desde'   => "$fecha $desde",
            'f_uso_hasta'   => "$fecha $hasta",
            'observaciones' => ''
        ];
        
        $id = DB::table('reservaciones')->insertGetId($params);
        
        if ( $id > 0 ) {
            
            $admin = DB::table('cuentas')
            ->where('nivel', 'admin')
            ->select('*')->first();
            
            $sala_info = DB::table('salas')
            ->where('sala_id', $sala)
            ->select('*')->first();
            
            if( is_object($admin) ) {
                $email = $admin->email;
                $nombre = session('cuenta')->nombre;
                $asunto = "Nueva Reservación de Sala";
                
                $params = [
                    'usuario'   => $nombre,
                    'sala'      => $sala_info->nombre,
                    'fecha'     => "$fecha $desde",
                    'url'       => (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST']
                ];
                
                $mensaje = view('mensajes.reservacion_nueva', $params);
                
                $this->notificar($email, $nombre, $asunto, $mensaje);
            }
            
            return view('mensajes.ok', [
                'mensaje' => 'La solicitud fue registrada. En espera de aprobación.'
                . view('reservar.refresh')
            ]);
        }
        else {
            return view('mensajes.error',
            [
                'mensaje' => 'Ocurrió un error al procesar la solicitud.'
            ]);
        }
        
    }

    public function salas($anio = '', $mes = '', $dia = '', $sala = '') {
        $desde = [
            '06:00:00'  => '06:00am.',
            '06:30:00'  => '06:30am.',
            '07:00:00'  => '07:00am.',
            '07:30:00'  => '07:30am.',
            '08:00:00'  => '08:00am.',
            '08:30:00'  => '08:30am.',
            '09:00:00'  => '09:00am.',
            '09:30:00'  => '09:30am.',
            '10:00:00'  => '10:00am.',
            '10:30:00'  => '10:30am.',
            '11:00:00'  => '11:00am.',
            '11:30:00'  => '11:30am.',
            '12:00:00'  => '12:00pm.',
            '12:30:00'  => '12:30pm.',
            '13:00:00'  => '01:00pm.',
            '13:30:00'  => '01:30pm.',
            '14:00:00'  => '02:00pm.',
            '14:30:00'  => '02:30pm.',
            '15:00:00'  => '03:00pm.',
            '15:30:00'  => '03:30pm.',
            '16:00:00'  => '04:00pm.',
            '16:30:00'  => '04:30pm.',
            '17:00:00'  => '05:00pm.',
            '17:30:00'  => '05:30pm.',
            '18:00:00'  => '06:00pm.',
            '18:30:00'  => '06:30pm.'
        ];
        
        $hasta = [
            '06:29:59'  => '06:30am.',
            '06:59:59'  => '07:00am.',
            '07:29:59'  => '07:30am.',
            '07:59:59'  => '08:00am.',
            '08:29:59'  => '08:30am.',
            '08:59:59'  => '09:00am.',
            '09:29:59'  => '09:30am.',
            '09:59:59'  => '10:00am.',
            '10:29:59'  => '10:30am.',
            '10:59:59'  => '11:00am.',
            '11:29:59'  => '11:30am.',
            '11:59:59'  => '12:00pm.',
            '12:29:59'  => '12:30pm.',
            '12:59:59'  => '01:00pm.',
            '13:29:59'  => '01:30pm.',
            '13:59:59'  => '02:00pm.',
            '14:29:59'  => '02:30pm.',
            '14:59:59'  => '03:00pm.',
            '15:29:59'  => '03:30pm.',
            '15:59:59'  => '04:00pm.',
            '16:29:59'  => '04:30pm.',
            '16:59:59'  => '05:00pm.',
            '17:29:59'  => '05:30pm.',
            '17:59:59'  => '06:00pm.',
            '18:29:59'  => '06:30pm.',
            '18:59:59'  => '07:00pm.',
        ];
        
        $params = [
            'desde' => $desde,
            'hasta' => $hasta,
            'fecha' => "$anio-$mes-$dia",
            'sala'  => $sala
        ];
        return view('reservar.horas', $params);
    }

    private function calendario($mes, $anio, $sala) {
        $dias = [
            'Dom.',
            'Lun.',
            'Mar.',
            'Mié.',
            'Jue.',
            'Vie.',
            'Sáb.'
        ];
        
        $_stamp = mktime(0, 0, 0, $mes, 1, $anio);
        $siguiente = mktime(0, 0, 0, $mes+1, 1, $anio);
        $anterior = mktime(0, 0, 0, $mes-1, 1, $anio);
        
        $mesSiguiente = date('m', $siguiente);
        $mesAnterior = date('m', $anterior);
        
        $anioSiguiente = date('Y', $siguiente);
        $anioAnterior = date('Y', $anterior);
        
        
        $semanas[0]=array();
        for ( $blank=0; $blank<date('w', $_stamp); $blank++ ) {
            $semanas[0][] = '&nbsp;';
        }
        
        
        for ( $dia = 1; $dia<=date('t', $_stamp); $dia++ ) {
            $dia_stamp = mktime(0, 0, 0, $mes, $dia, $anio);
            $semanas[count($semanas)-1][] = str_pad($dia, 2, '0', STR_PAD_LEFT);
            if ( date('w', $dia_stamp) == 6 ) {
                $semanas[count($semanas)] = array();
            }
        }
        
        $mesTexto = $this->mesTexto($mes);
        
        $salas = DB::table('salas')->orderBy('nombre')->select('*')->get();
        
        $params = [
            'dias'      => $dias,
            'semanas'   => $semanas,
            'mes'       => $mes,
            'mesTexto'  => $mesTexto,
            'anio'      => $anio,
            'anioSiguiente' => $anioSiguiente,
            'anioAnterior'  => $anioAnterior,
            'mesSiguiente'  => $mesSiguiente,
            'mesAnterior'   => $mesAnterior,
            'salas'         => $salas,
            'sala_seleccionada' => $sala
        ];
        return view('reservar.calendario', $params);
    }

    private function mesTexto($mes) {
        switch ( $mes ) {
            case '1': return 'Enero';break;
            case '2': return 'Febrero';break;
            case '3': return 'Marzo';break;
            case '4': return 'Abril';break;
            case '5': return 'Mayo';break;
            case '6': return 'Junio';break;
            case '7': return 'Julio';break;
            case '8': return 'Agosto';break;
            case '9': return 'Septiembre';break;
            case '10': return 'Octubre';break;
            case '11': return 'Noviembre';break;
            case '12': return 'Diciembre';break;
        }
        return '';
    }

    public function autorizar($reservacion='', Request $request) {
        if ( session('cuenta')->nivel == 'admin' ) {
            $comentario = $request->input('comentario');
            DB::table('reservaciones')
            ->where('reservacion_id', $reservacion)
            ->update(['estado'=>'Autorizado', 'observaciones'=>$comentario]);

            $this->notificacion($reservacion);
            return view('reservar.recargar');
        }
        else {
            return view('mensajes.no_autorizado');
        }
    }

    public function rechazar($reservacion='', Request $request) {
        if ( session('cuenta')->nivel == 'admin' ) {
            $comentario = $request->input('comentario');
            DB::table('reservaciones')
            ->where('reservacion_id', $reservacion)
            ->update(['estado'=>'Rechazado', 'observaciones'=>$comentario]);
            $this->notificacion($reservacion);
        }
        else {
            return view('mensajes.no_autorizado');
        }    
        
        return view('reservar.recargar');
    }

    public function cancelar($reservacion=''){
        DB::table('reservaciones')
        ->where([
            ['reservacion_id', $reservacion],
            ['cuenta', session('cuenta')->cuenta_id]
                ]
        )->update(['estado'=>'Cancelado']);
        
        return view('reservar.recargar');
    }

    public function eliminar(Request $request) {
        if ( session('cuenta')->nivel == 'admin' ) {
            $reservacion_id = $request->input('reservacion');
            DB::table('reservaciones')->where('reservacion_id', $reservacion_id)
            ->delete();
            return view('cuentas.eliminada', [
                'cuenta_id' => $reservacion_id
            ]);
        }
        else {
            return view('mensajes.no_autorizado');
        }
    }

    
}
