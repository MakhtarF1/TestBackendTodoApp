<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::apiResource('tasks', TaskController::class);

// Pour tester que l'API fonctionne
Route::get('/test', function() {
    return response()->json(['message' => 'API fonctionne correctement']);
});