<?php

use App\Http\Controllers\Company;
use App\Http\Controllers\Login;
use App\Http\Controllers\Permissions;
use App\Http\Controllers\ProtectedDownloads;
use App\Http\Controllers\Userlist;
use App\Http\Controllers\Utils;
use App\Http\Controllers\logs;
use App\Http\Controllers\logsErrosController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\SMTP;

use App\Http\Controllers\ConfigCarros;
use App\Http\Controllers\ConfigMotos;
// ALTERAHEAD


use App\Models\Benefit;
use App\Models\Office as ModelsOffice;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

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


Route::middleware(['auth', 'has.temp.password'])->group(function () {
    Route::get('/usuarios', [Userlist::class, 'index'])->name('list.users');

    Route::get('usuarios/criar', [Userlist::class, 'create'])
        ->name('form.store.user');

    Route::post('usuarios/criar', [Userlist::class, 'store'])
        ->name('store.user');

    Route::get('usuarios/editar/{user_id}', [Userlist::class, 'edit'])
        ->name('form.update.user');

    Route::post('usuarios/editar/{user_id}', [Userlist::class, 'update'])
    ->name('update.user');

    Route::get('Profile', [Userlist::class, 'editProfile'])
    ->name('form.update.profile');

    Route::post('Profile', [Userlist::class, 'updateProfile'])
        ->name('update.userProfile');

        
    Route::post('usuarios/{user_id}', [Userlist::class, 'delete'])
        ->name('form.delete.user');

    Route::get('usuarios/recuperar-senha-interno/{user_id}', [Userlist::class, 'resendPassword'])
        ->name('resend.password.user');


    Route::get('empresas', [Company::class, 'index'])->name('list.company');
    Route::get('empresas/criar', [Company::class, 'create'])->name('form.store.company');
    Route::post('empresas/criar', [Company::class, 'store'])->name('store.company');
    Route::get('empresas/editar/{id}', [Company::class, 'edit'])->name('form.update.company');
    Route::post('empresas/editar/{id}', [Company::class, 'update'])->name('update.company');
    Route::post('empresas/deletar/{id}', [Company::class, 'delete'])->name('delete.company');


    Route::get('permissoes', [Permissions::class, 'render'])
        ->name('list.permission');

    Route::get('permissoes/criar', [Permissions::class, 'create'])
        ->name('form.store.permission');

    Route::post('permissoes/criar', [Permissions::class, 'store'])
        ->name('store.permission');

    Route::get('permissoes/editar/{permission_id}', [Permissions::class, 'edit'])
        ->name('form.update.permission');

    Route::post('permissoes/editar/{permission_id}', [Permissions::class, 'update'])
        ->name('update.permission');

    Route::post('permissoes/{permission_id}', [Permissions::class, 'delete'])
        ->name('form.delete.permission');

    Route::get('get-files/{filename?}', [ProtectedDownloads::class, 'showJobImage'])
        ->name('get.files');

    Route::get('download-files/{path}', [ProtectedDownloads::class, 'download'])->name('download.files');
    Route::get('download2-files/{path}', [ProtectedDownloads::class, 'download2'])->name('download2.files');





    Route::get('/index', function () {  return redirect()->route('list.Dashboard');   })->name('home');
    Route::get('/', function () {  return redirect()->route('list.Dashboard');   });




    Route::get('logs', [logs::class, 'index'])->name('list.logs');
    Route::get('logs/criar', [logs::class, 'create'])->name('form.store.logs');
    Route::post('logs/criar', [logs::class, 'store'])->name('store.logs');
    Route::get('logs/editar/{id}', [logs::class, 'edit'])->name('form.update.logs');
    Route::post('logs/editar/{id}', [logs::class, 'update'])->name('update.logs');
    Route::post('logs/deletar/{id}', [logs::class, 'delete'])->name('delete.logs');

    
	
	Route::get('logsErros', [logsErrosController::class, 'index'])->name('list.logsErros');
    Route::get('logsErros/criar', [logsErrosController::class, 'create'])->name('form.store.logsErros');
    Route::post('logsErros/criar', [logsErrosController::class, 'store'])->name('store.logsErros');
    Route::get('logsErros/editar/{id}', [logsErrosController::class, 'edit'])->name('form.update.logsErros');
    Route::post('logsErros/editar/{id}', [logsErrosController::class, 'update'])->name('update.logsErros');
    Route::post('logsErros/deletar/{id}', [logsErrosController::class, 'delete'])->name('delete.logsErros');




Route::get('logsUsuario', [logs::class, 'index'])->name('list.logsUsuario');
    Route::get('logsUsuario/criar', [logs::class, 'create'])->name('form.store.logsUsuario');
    Route::post('logsUsuario/criar', [logs::class, 'store'])->name('store.logsUsuario');
	Route::post('logsUsuario/criarAjax', [logs::class, 'storeAjax'])->name('storeAjax.logsUsuario');
    Route::get('logsUsuario/editar/{id}', [logs::class, 'edit'])->name('form.update.logsUsuario');
    Route::post('logsUsuario/editar/{id}', [logs::class, 'update'])->name('update.logsUsuario');
	Route::post('logsUsuario/editar/{id}', [logs::class, 'updateAjax'])->name('updateAjax.logsUsuario');
    Route::post('logsUsuario/deletar/{id}', [logs::class, 'delete'])->name('delete.logsUsuario');
	Route::post('logsUsuario/deletar/{id}', [logs::class, 'deleteAjax'])->name('deleteAjax.logsUsuario');


    Route::get('SMTP/editar', [SMTP::class, 'edit'])->name('list.SMTP');
    Route::post('SMTP/editar/{id}', [SMTP::class, 'update'])->name('update.SMTP');



Route::get('ConfigCarros', [ConfigCarros::class, 'index'])->name('list.ConfigCarros');
	Route::post('ConfigCarros', [ConfigCarros::class, 'index'])->name('listP.ConfigCarros');
    Route::get('ConfigCarros/criar', [ConfigCarros::class, 'create'])->name('form.store.ConfigCarros');
    Route::post('ConfigCarros/criar', [ConfigCarros::class, 'store'])->name('store.ConfigCarros');
    Route::get('ConfigCarros/editar/{id}', [ConfigCarros::class, 'edit'])->name('form.update.ConfigCarros');
    Route::post('ConfigCarros/editar/{id}', [ConfigCarros::class, 'update'])->name('update.ConfigCarros');
    Route::post('ConfigCarros/deletar/{id}', [ConfigCarros::class, 'delete'])->name('delete.ConfigCarros');
	Route::post('ConfigCarros/deletarSelecionados/{id?}', [ConfigCarros::class, 'deleteSelected'])->name('deleteSelected.ConfigCarros');
	Route::post('ConfigCarros/deletarTodos', [ConfigCarros::class, 'deletarTodos'])->name('deletarTodos.ConfigCarros');
	Route::post('ConfigCarros/RestaurarTodos', [ConfigCarros::class, 'RestaurarTodos'])->name('RestaurarTodos.ConfigCarros');
	Route::get('ConfigCarros/RelatorioExcel', [ConfigCarros::class, 'exportarRelatorioExcel'])->name('get.Excel.ConfigCarros');
    Route::post('AlugadoCarro', [ConfigCarros::class, 'alugado'])->name('store.AlugadoCarro');
    Route::get('aluguelCarros', [ConfigCarros::class, 'aluguelCarros'])->name('list.aluguelCarros');
    Route::get('telaAluguelCarros/{id}', [ConfigCarros::class, 'telaAluguel'])->name('telaAluguelCarros');
    Route::post('alugandoCarro/{id}', [ConfigCarros::class, 'alugando'])->name('alugandoCarro');
    Route::get('vendaCarros', [ConfigCarros::class, 'vendaCarros'])->name('list.vendaCarros');
    Route::post('comprandoCarro/{id}', [ConfigCarros::class, 'comprandoCarro'])->name('comprandoCarro');
    Route::get('telaCompraCarro/{id}', [ConfigCarros::class, 'telaCompraCarro'])->name('telaCompraCarro');
    Route::post('compradoCarros', [ConfigCarros::class, 'compradoCarros'])->name('compradoCarros');
    
    Route::get('ConfigMotos', [ConfigMotos::class, 'index'])->name('list.ConfigMotos');
	Route::post('ConfigMotos', [ConfigMotos::class, 'index'])->name('listP.ConfigMotos');
    Route::get('ConfigMotos/criar', [ConfigMotos::class, 'create'])->name('form.store.ConfigMotos');
    Route::post('ConfigMotos/criar', [ConfigMotos::class, 'store'])->name('store.ConfigMotos');
    Route::get('ConfigMotos/editar/{id}', [ConfigMotos::class, 'edit'])->name('form.update.ConfigMotos');
    Route::post('alugado', [ConfigMotos::class, 'alugado'])->name('store.Alugado');
    Route::get('aluguelMotos', [ConfigMotos::class, 'aluguelMotos'])->name('list.aluguelMotos');
    Route::get('telaAluguel/{id}', [ConfigMotos::class, 'telaAluguel'])->name('telaAluguel');
    Route::post('alugando/{id}', [ConfigMotos::class, 'alugando'])->name('alugando');
    Route::post('ConfigMotos/editar/{id}', [ConfigMotos::class, 'update'])->name('update.ConfigMotos');
    Route::post('ConfigMotos/deletar/{id}', [ConfigMotos::class, 'delete'])->name('delete.ConfigMotos');
	Route::post('ConfigMotos/deletarSelecionados/{id?}', [ConfigMotos::class, 'deleteSelected'])->name('deleteSelected.ConfigMotos');
	Route::post('ConfigMotos/deletarTodos', [ConfigMotos::class, 'deletarTodos'])->name('deletarTodos.ConfigMotos');
	Route::post('ConfigMotos/RestaurarTodos', [ConfigMotos::class, 'RestaurarTodos'])->name('RestaurarTodos.ConfigMotos');
	Route::get('ConfigMotos/RelatorioExcel', [ConfigMotos::class, 'exportarRelatorioExcel'])->name('get.Excel.ConfigMotos');
    Route::get('vendaMotos', [ConfigMotos::class, 'vendaMotos'])->name('list.vendaMotos');
    Route::post('comprando/{id}', [ConfigMotos::class, 'comprando'])->name('comprando');
    Route::get('telaCompraMoto/{id}', [ConfigMotos::class, 'telaCompraMoto'])->name('telaCompraMoto');
    Route::post('compradoMotos', [ConfigMotos::class, 'compradoMotos'])->name('compradoMotos');


// #ModificaAqui

   
    Route::get('Dashboard/Calendario', [Dashboard::class, 'Calendario'])->name('list.DashboardCalendario');
    Route::get('Dashboard/{id?}', [Dashboard::class, 'index'])->name('list.Dashboard');


    Route::get('cep/{cep}', [Utils::class, 'getAddressViaCep'])->name('get.address.viacep');

    

    Route::post('toggle-column-table/', [Utils::class, 'toggleColumnsTables'])
        ->name('toggle.columns.tables');

    Route::post('/logout', [Login::class, 'logout'])->name('logout');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/nova-senha', [Login::class, 'replaceTempPasswordView'])->name('temp.password');
    Route::post('/nova-senha', [Login::class, 'replaceTempPassword'])->name('send.temp.password');
});



Route::get('/login', [Login::class, 'index'])->name('login');

Route::post('/login', [Login::class, 'login'])->name('action.login');

Route::get('/esqueci-minha-senha', [Login::class, 'forgotPassword'])->name('forgot.password');

Route::get('/cadastro', [Login::class, 'cadastro'])->name('cadastro');

Route::POST('/cadastrar', [Login::class, 'cadastrar'])->name('cadastrar');

Route::post('/esqueci-minha-senha', [Login::class, 'recoveryPasswordSend'])->name('forgot.password.send');

Route::get('/recuperar-minha-senha', [Login::class, 'recoveryPassword'])->name('recovery.password');

Route::get('/recuperar-minha-senha/{code}', [Login::class, 'recoveryPassword'])->name('recovery.password');

Route::post('/recuperar-minha-senha/{code}', [Login::class, 'recoveryPasswordSend'])->name('recovery.password.send');