<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('search',[HomeController::class,'buscarPaciente'])->name('search');

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
use App\Http\Controllers\ProntuarioController;

Route::match(['get', 'post'], '/prontuario/cadastrar/{id}', [HomeController::class, 'cadastrarProntuario'])->name('prontuario.cadastrar');
// Route::match(['get', 'post'], '/agenda/cadastrar/{id}',     [HomeController::class, 'agendaConsulta'])->name('agenda.cadastrar');
Route::match(['get', 'post'], '/usuario/{cpf}',             [HomeController::class, 'getUserByCpf'])->name('usuario.getByCpf');
Route::get('agenda',                                        [HomeController::class, 'agenda'])->name('agenda');
Route::get('busca_cpf',                                     [HomeController::class, 'getUserByCpf'])->name('cpf');
Route::get('agenda_consulta',                               [HomeController::class, 'agendaConsulta'])->name('agenda_consulta');
Route::post('agenda_consulta',                              [HomeController::class, 'agendaConsulta']);
Route::get('/atendimento/{id}',                             [HomeController::class, 'atendimento'])->name('atendimento');

Route::controller(LoginController::class)->group(function(){
    Route::post('login', [LoginController::class, 'login'])->name('login.store');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
});



