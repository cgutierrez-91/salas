<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
    //return view('welcome');
//});

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', function () {
    $user = session('cuenta');
    if ( empty($user) ) {
        return view("login");
    }
    else {
        $params = [
            'usuario' => $user->nombre
        ];
        return view('inicio', $params);
    }
});


Route::post('/entrar', 'LoginController@entrar');
Route::get('/salir', 'LoginController@salir');


// CUENTAS
Route::get('/cuentas', 'CuentasController@index');
Route::get('/cuentas/nueva', 'CuentasController@nueva');
Route::get('/cuentas/editar/{id}', 'CuentasController@editar');
Route::post('/cuentas/crear', 'CuentasController@crear');
Route::post('/cuentas/disponible/', 'CuentasController@disponible');
Route::post('/cuentas/eliminar', 'CuentasController@eliminar');
Route::post('/cuentas/actualizar', 'CuentasController@actualizar');
Route::get('/cuentas/password/{id}', 'CuentasController@password');
Route::post('/cuentas/password_actualizar', 'CuentasController@password_actualizar');

// SALAS
Route::get('/salas/', 'SalasController@index');
Route::get('/salas/nueva', 'SalasController@nueva');
Route::get('/salas/editar/{id}', 'SalasController@editar');

Route::post('/salas/crear', 'SalasController@crear');
Route::post('/salas/actualizar', 'SalasController@actualizar');
Route::post('/salas/eliminar', 'SalasController@eliminar');


// RESERVAR
Route::get('/reservar/', 'ReservarController@index');
Route::get('/reservar/nueva/{anio}/{mes}/{sala?}', 'ReservarController@nueva');
Route::get('/reservar/nueva/{anio}', 'ReservarController@nueva');
Route::get('/reservar/nueva', 'ReservarController@nueva');
Route::get('/reservar/sala/{anio}/{mes}/{dia}/{sala}', 'ReservarController@salas');
Route::get('/reservar', 'ReservarController@index');
Route::post('/reservar/autorizar/{reservacion}', 'ReservarController@autorizar');
Route::post('/reservar/rechazar/{reservacion}', 'ReservarController@rechazar');
Route::get('/reservar/cancelar/{reservacion}', 'ReservarController@cancelar');

Route::post('/reservar/sala', 'ReservarController@reservar');
Route::post('/reservar', 'ReservarController@index');
Route::post('/reservar/eliminar/', 'ReservarController@eliminar');


// PERFIL
Route::get('/usuario/perfil/', 'PerfilController@perfil');
Route::get('/usuario/perfil/clave', 'PerfilController@clave');

Route::post('/usuario/perfil/clave', 'PerfilController@clave_actualizar');
Route::post('/usuario/perfil/actualizar/', 'PerfilController@actualizar');
