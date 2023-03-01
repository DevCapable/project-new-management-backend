<?php

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'apicontroller@login');
Route::group(['middleware' => ['auth:sanctum']], function () {   
    Route::post('logout', 'apicontroller@logout');
    Route::get('/get-projects', 'apicontroller@getProjects');
     Route::get('/get-workspace', 'apicontroller@getworkspace');
    Route::post('add-tracker', 'apicontroller@addTracker');
    Route::post('stop-tracker', 'apicontroller@stopTracker');
    Route::post('upload-photos', 'apicontroller@uploadImage');
});
