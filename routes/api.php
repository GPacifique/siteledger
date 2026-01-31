<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectApiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// CPM API example routes
Route::prefix('cpm')->middleware('auth:sanctum')->group(function() {
    Route::get('projects', [ProjectApiController::class, 'index']);
    Route::post('projects', [ProjectApiController::class, 'store']);
    Route::get('projects/{project}', [ProjectApiController::class, 'show']);
    Route::put('projects/{project}', [ProjectApiController::class, 'update']);
    Route::delete('projects/{project}', [ProjectApiController::class, 'destroy']);
});
