<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResearcherController;

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

// Rutas ORCID API
Route::prefix('orcid')->group(function () {
    Route::post('/create', [ResearcherController::class, 'store']);
    Route::put('/update', [ResearcherController::class, 'update']);
    Route::delete('/delete/{orcid}', [ResearcherController::class, 'destroy']);
    Route::get('/list', [ResearcherController::class, 'index']);
    Route::get('/{orcid}', [ResearcherController::class, 'show']);
});
