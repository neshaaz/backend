<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransaksiController;

Route::post('/auth/register',[AuthController::class, 'register']);
Route::post('/auth/login',[AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Info user login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // CRUD Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index']);
    Route::post('/transaksi', [TransaksiController::class, 'store']);
    Route::put('/transaksi/{id}', [TransaksiController::class, 'update']);
    Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy']);

    // dashboard & rekap
    Route::get('/transaksi-dashboard', [TransaksiController::class, 'dashboard']);
    Route::get('/transaksi-rekap', [TransaksiController::class, 'rekap']);
});

// // TEST API 
// Route::get('/test', function () {
//     return response()->json([
//         'message' => 'API jalan'
//     ]);
// });