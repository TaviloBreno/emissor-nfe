<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API para listagem de notas (usado nos testes)
Route::middleware('auth')->get('/notas', function () {
    return \App\Models\NotaFiscal::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();
});
