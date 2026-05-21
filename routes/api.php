<?php

use App\Http\Controllers\Api\AdminOrderController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;
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

    Route::patch('/admin/users/{id}/make-admin', [AdminUserController::class, 'makeAdmin']);

    Route::patch('/admin/users/{id}/remove-admin', [AdminUserController::class, 'removeAdmin']);

    Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy']);

    // Admin - Orders
    Route::get('/admin/orders', [AdminOrderController::class, 'index']);
    Route::get('/admin/orders/{id}', [AdminOrderController::class, 'show']);
    Route::put('/admin/orders/{id}/status', [AdminOrderController::class, 'updateStatus']);
    Route::delete('/admin/orders/{id}', [AdminOrderController::class, 'destroy']);
});

// Mueve esto FUERA de cualquier grupo 'auth:sanctum'
Route::get('/categories', [CategoryController::class, 'index']);

// Deja el resto de operaciones (store, update, delete) protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::post('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
});

// Rutas Públicas de Artículos
Route::get('/articles', [ArticleController::class, 'index']); // GET /api/articles?in_stock=true
Route::get('/articles/{id}', [ArticleController::class, 'show']);

// 📄 Ruta Pública de Reseñas: Cualquiera puede ver las opiniones de un artículo
Route::get('/articles/{article}/reviews', [ReviewController::class, 'index']);

// Rutas Protegidas (Crear, Editar, Borrar, Comprar)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::post('/articles/{id}', [ArticleController::class, 'update']); // Usamos POST por las imágenes
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);

    // Ruta personalizada para la compra
    Route::post('/articles/{id}/buy', [ArticleController::class, 'buy']);

    // Client - Orders
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    // 💬 Ruta Protegida de Reseñas: Solo usuarios logueados pueden comentar
    Route::post('/articles/{article}/reviews', [ReviewController::class, 'store']);
    // En routes/api.php (Dentro del grupo Route::middleware('auth:sanctum')->group(...))
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
});