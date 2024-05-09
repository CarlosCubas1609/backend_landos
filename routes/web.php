<?php

use App\Models\Orden;
use Illuminate\Support\Facades\Mail;
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

Route::get('/ruta', function () {
    $orden = Orden::find(1);
    $servicio = $orden->servicio_model;

    Mail::send('mail.orden', ['orden' => $orden,'servicio' => $servicio], function ($mail) use ($orden) {
        $mail->to("ccubas@unitru.edu.pe");
        $mail->subject('Landos (tu orden de servicio)');
        $mail->from('kidaddy20@gmail.com', 'LANDOS');
    });

    return "ok";
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
