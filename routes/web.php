<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategorisController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/master-items', [App\Http\Controllers\MasterItemsController::class, 'index']);
Route::get('/master-items/search', [App\Http\Controllers\MasterItemsController::class, 'search']);
Route::get('/master-items/form/{method}/{id?}', [App\Http\Controllers\MasterItemsController::class, 'formView']);
Route::post('/master-items/form/{method}/{id?}', [App\Http\Controllers\MasterItemsController::class, 'formSubmit']);

Route::get('/master-items/download-excel', [App\Http\Controllers\MasterItemsController::class, 'downloadExcel']);
Route::get('/master-items/view/{kode}', [App\Http\Controllers\MasterItemsController::class, 'singleView']);
Route::get('/master-items/delete/{id}', [App\Http\Controllers\MasterItemsController::class, 'delete']);


Route::get('/master-items/update-random-data', [App\Http\Controllers\MasterItemsController::class, 'updateRandomData']);

Route::get('/kategoris', [KategorisController::class, 'index']);
Route::get('/kategoris/search', [KategorisController::class, 'search']);
Route::get('/kategoris/form/{method}/{id?}', [KategorisController::class, 'formView']);
Route::post('/kategoris/form/{method}/{id?}', [KategorisController::class, 'formSubmit']);
Route::get('/kategoris/view/{kode}', [KategorisController::class, 'singleView']);
Route::get('/kategoris/delete/{id}', [KategorisController::class, 'delete']);
Route::get('/kategoris/download-pdf/{kode}', [KategorisController::class, 'downloadPdf']);
