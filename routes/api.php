<?php

use App\Http\Controllers\GanttController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/data', 'App\Http\Controllers\GanttController@get');
// Route::middleware('auth:sanctum')->group(function () {
    Route::get('data/{id}', [App\Http\Controllers\GanttController::class, 'get']);
// });
// Route::post('task', [App\Http\Controllers\TaskGanttController::class, 'update']);
// this is for updating the database
// Route::resource('task', App\Http\Controllers\TaskGanttController::class);
