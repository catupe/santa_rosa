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

Route::get('/', function () {
    return view('welcome');
});
Route::get('user/{id?}', function( $id = 0 ) {
    return 'User '.$id;
})
->where('id', '[0-9]+');

Auth::routes();
Route::any('cambiar_password', 'UserController@cambiar_password');

//// balanzas
Route::any('balanzas', 'BalanzaController@getLecturas');
Route::any('calculo', 'BalanzaController@calculo');
Route::post('editar_lectura', 'BalanzaController@editarLectura');
/*
Route::any('balanzas', [
                        'as'          => 'balanzas',
                        'middleware'  => 'role:admin',
                        'uses'        => 'BalanzaController@getLecturas',
                      ]);
*/
/*
Route::get('login', [
                      'as' => 'login',
                      'uses' => 'Auth\LoginController@showLoginForm'
]);
Route::post('login', [
                      'as' => 'login',
                      'uses' => 'Auth\LoginController@showLoginForm'
]);
Route::post('logout', [
                      'as' => 'logout',
                      'uses' => 'Auth\LoginController@logout'
]);
*/
/*
Route::group(['middleware' => 'auth'], function () {
	Route::get('login', 'Auth\LoginController@showLoginForm');
});
*/
Route::get('/home/{nombre}', 'HomeController@index');

Route::get('ejemplo', function( $id = 0 ) {
    return view('ejemplo.ppal');
});

//Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
