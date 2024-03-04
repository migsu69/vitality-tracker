<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\VitalsController;
use App\Http\Controllers\EmployeesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [EmployeesController::class, 'index'])->name('index');
Route::get('/login/{employee_id}', [LoginController::class, 'login'])->name('login');
Route::post('/dashboard/{employee_id}/vitals', [VitalsController::class, 'store'])->name('vitals.store');
Route::put('/vitals/{vital}', [VitalsController::class, 'update'])->name('vitals.update');
Route::get('/dashboard/{employee_id}', [EmployeesController::class, 'dashboard'])->name('dashboard');
Route::delete('/vitals/{vital}/destroy', [VitalsController::class, 'destroy'])->name('vitals.destroy');
/* Route::get('/dashboard/{employee_id}', [VitalsController::class, 'filterVitals'])->name('filter-vitals'); */


// Route::group(['middleware' => ['web', 'auth']], function () {


// });
Route::any('/logout', [LoginController::class, 'logout'])->name('logout');

use App\Http\Controllers\ExcelController;
Route::get('/export/{employee_id}', [ExcelController::class, 'export'])->name('export');

