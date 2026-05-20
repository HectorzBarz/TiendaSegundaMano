<?php

use App\Http\Controllers\Api\AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas (Requieren enviar el Token en los Headers)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user/profile', [ProfileController::class, 'show']);

    // Usamos POST en lugar de PUT/PATCH porque la actualización incluye archivos (imágenes)
    // y PHP maneja mejor los form-data multipart con peticiones POST.
    Route::post('/user/profile', [ProfileController::class, 'update']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/admin/users', [AdminUserController::class, 'index']);

    Route::get('/admin/users/{id}', [AdminUserController::class, 'show']);

    Route::post('/admin/users/{id}', [AdminUserController::class, 'update']);

});