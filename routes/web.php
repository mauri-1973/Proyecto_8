<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('datatables-inicial', [App\Http\Controllers\HomeController::class, 'datatablesinicial'])->name('datatables-inicial');
Route::post('registro-de-usuario', [App\Http\Controllers\HomeController::class, 'regus'])->name('registro-de-usuario');
Route::post('editar-usuario', [App\Http\Controllers\HomeController::class, 'editus'])->name('editar-usuario');
Route::post('edicion-de-usuario-id', [App\Http\Controllers\HomeController::class, 'editusid'])->name('edicion-de-usuario-id');
Route::post('eliminar-usuario-id', [App\Http\Controllers\HomeController::class, 'eliusid'])->name('eliminar-usuario-id');
Route::post('agregar-vehiculo', [App\Http\Controllers\HomeController::class, 'addveh'])->name('agregar-vehiculo');
Route::post('historial-vehiculo-usuario', [App\Http\Controllers\HomeController::class, 'histusveh'])->name('historial-vehiculo-usuario');
/* Controlador para detectar cambio de idiomas */
Route::get('lang/{lang}', 'App\Http\Controllers\LanguageController@swap')->name('lang.swap');

