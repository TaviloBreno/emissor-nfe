<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotaFiscalController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\ConfiguracaoController;

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

// Rotas de autenticação
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// API Routes (manter compatibilidade)
Route::post('/api/notas', [NotaFiscalController::class, 'storeApi'])->name('api.notas.store');
Route::post('/notas/{id}/cancelar', [NotaFiscalController::class, 'cancelar']);
Route::post('/notas/inutilizar', [NotaFiscalController::class, 'inutilizar']);
Route::get('/notas/inutilizacoes', [NotaFiscalController::class, 'consultarInutilizacoes']);
Route::post('/notas/{id}/correcao', [NotaFiscalController::class, 'emitirCartaCorrecao']);
Route::post('/notas/{id}/manifestar', [NotaFiscalController::class, 'manifestar']);

// Web Routes
Route::middleware('auth')->group(function () {
    // Notas Fiscais
    Route::get('/notas', [NotaFiscalController::class, 'index'])->name('notas.index');
    Route::get('/notas/criar', [NotaFiscalController::class, 'create'])->name('notas.create');
    Route::post('/notas', [NotaFiscalController::class, 'store'])->name('notas.store');
    Route::get('/notas/{id}', [NotaFiscalController::class, 'show'])->name('notas.show');
    Route::get('/notas/{id}/download', [NotaFiscalController::class, 'downloadXml'])->name('notas.download');
    Route::get('/notas/{id}/xml', [NotaFiscalController::class, 'downloadXml'])->name('notas.xml');
    
    // Relatórios
    Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
    Route::post('/relatorios/exportar', [RelatorioController::class, 'exportar'])->name('relatorios.exportar');
    
    // Configurações
    Route::get('/configuracoes', [ConfiguracaoController::class, 'index'])->name('configuracoes.index');
    Route::put('/configuracoes/profile', [ConfiguracaoController::class, 'updateProfile'])->name('configuracoes.profile');
    Route::put('/configuracoes/password', [ConfiguracaoController::class, 'updatePassword'])->name('configuracoes.password');
    Route::put('/configuracoes/emitente', [ConfiguracaoController::class, 'updateEmitente'])->name('configuracoes.emitente');
});
