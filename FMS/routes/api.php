<?php

use App\Http\Controllers\FileController;
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

Route::get('allImages',[FileController::class, 'allImages']);
Route::get('get_first_image',[FileController::class, 'getFirstImage']);
Route::get('get_last_image',[FileController::class, 'getLastImage']);

Route::get('get_limit_image_desc',[FileController::class, 'getLimitImageDesc']);
Route::get('get_limit_image_asc',[FileController::class, 'getLimitImageAsc']);
Route::get('get_image_between',[FileController::class, 'getImageBetween']);

Route::post('create',[FileController::class, 'create']);
Route::post('update',[FileController::class, 'update']);
Route::post('delete',[FileController::class, 'delete']);
