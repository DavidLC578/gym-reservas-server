<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/saludo', function (Request $request) {
    return "Hola mundo";
});

// Routes for authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:api'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
});

// Routes for classes (CRUD)
Route::get('/classes', [ClassController::class, 'list']);
Route::get('/classes/{id}', [ClassController::class, 'listOne']);
Route::post('/classes', [ClassController::class, 'store']);
Route::put('/classes/{id}', [ClassController::class, 'update']);
Route::delete('/classes/{id}', [ClassController::class, 'delete']);

// Routes for class enrollment
Route::post('/classes/{classId}/enroll', [ClassController::class, 'enroll']);
Route::get('/classes/{classId}/participants', [ClassController::class, 'getClassParticipants']);
Route::delete('/classes/{classId}/cancel-enrollment', [ClassController::class, 'cancelEnrollment']);
