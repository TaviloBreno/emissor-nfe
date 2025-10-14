<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotaFiscalController;

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

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

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
    Route::get('/notas', [NotaFiscalController::class, 'index'])->name('notas.index');
    Route::get('/notas/criar', [NotaFiscalController::class, 'create'])->name('notas.create');
    Route::post('/notas', [NotaFiscalController::class, 'store'])->name('notas.store');
    Route::get('/notas/{id}', [NotaFiscalController::class, 'show'])->name('notas.show');
    Route::get('/notas/{id}/download', [NotaFiscalController::class, 'downloadXml'])->name('notas.download');
    Route::get('/notas/{id}/xml', [NotaFiscalController::class, 'downloadXml'])->name('notas.xml');
});
