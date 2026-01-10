<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Api\TransaksiController;

Route::post('/auth/register',[AuthController::class, 'register']);
Route::post('/auth/login',[AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// // TEST API 
// Route::get('/test', function () {
//     return response()->json([
//         'message' => 'API jalan'
//     ]);
// });

// // AUTH LOGIN & REGISTER
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);

// // USER PROFILE
// Route::get('/user/{id}', [AuthController::class, 'profile']);

// // TRANSAKSI
// Route::get('/transaksi', [TransaksiController::class, 'index']);
// Route::get('/transaksi/{id}', [TransaksiController::class, 'show']); 
// Route::post('/transaksi', [TransaksiController::class, 'store']);
// Route::put('/transaksi/{id}', [TransaksiController::class, 'update']);
// Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy']); 