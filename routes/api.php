<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ApiEmployeeController;
use App\Http\Controllers\ApiDepartmentController;
use App\Http\Controllers\ApiScheduleController;
use App\Http\Controllers\ApiLocationController;
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

Route::controller(ApiController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    // Route::post('/captureAttendanceOffline', 'captureAttendanceOffline');
});
Route::post('/sendResetPasswordEmail', [LoginController::class, 'sendResetPasswordEmail']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/department', [ApiController::class, 'getDepartments']);
    Route::post('/uploadImage', [ApiController::class, 'uploadImage']);
    Route::post('/logout', [ApiController::class, 'logout']);
    Route::post('/captureAttendance', [ApiController::class, 'captureAttendance']);
    Route::post('/punchOutAttendance', [ApiController::class, 'punchOutAttendance']);
    Route::post('/captureTimeOff', [ApiController::class, 'captureTimeOff']);
    Route::post('/punchOutTimeOff', [ApiController::class, 'punchOutTimeOff']);
    Route::post('/captureOutsideActivity', [ApiController::class, 'captureOutsideActivity']);
    Route::post('/captureAttendanceOffline', [ApiController::class, 'captureAttendanceOffline']);
});


//Employee Controller api
Route::middleware(['auth:sanctum'])->prefix('employees')->group(function () {
    Route::get('index', [ApiEmployeeController::class, 'getAllEmployees']);
    Route::post('add', [ApiEmployeeController::class, 'addEmployee']);
    Route::post('upload-excel', [ApiEmployeeController::class, 'uploadExcel']);
    Route::put('edit', [ApiEmployeeController::class, 'editEmployee']);
    Route::delete('delete', [ApiEmployeeController::class, 'deleteEmployee']);
    Route::get('allAttendance', [ApiEmployeeController::class, 'attendances']);
    // Route::get('allAttendance2', [ApiEmployeeController::class, 'getAllAttendancesByDepartment']);
    // Route::get('allAttendance3', [ApiEmployeeController::class, 'getAllUserByDepartmentIdAndLocId']);
    Route::get('timeoffListView', [ApiEmployeeController::class, 'timeoffListView']);
});


//Department Controller Api
Route::middleware(['auth:sanctum'])->prefix('departments')->group(function () {
    Route::get('index', [ApiDepartmentController::class, 'getAllDepartment']);
    Route::post('add', [ApiDepartmentController::class, 'addDepartment']);
    Route::put('edit', [ApiDepartmentController::class, 'editDepartment']);
    Route::delete('delete', [ApiDepartmentController::class, 'deleteDepartment']);
    
});

//Schedule Controller api
Route::middleware(['auth:sanctum'])->prefix('schedule')->group(function () {
    Route::get('index', [ApiScheduleController::class, 'getAllSchedule']);
    Route::post('add', [ApiScheduleController::class, 'addSchedule']);
    Route::put('edit', [ApiScheduleController::class, 'editSchedule']);
    Route::delete('delete', [ApiScheduleController::class, 'deleteSchedule']);
    
});


//Location controller api
Route::middleware(['auth:sanctum'])->prefix('location')->group(function () {
    Route::get('index', [ApiLocationController::class, 'getAllLocations']);
    Route::post('add', [ApiLocationController::class, 'addLocation']);
    Route::put('edit', [ApiLocationController::class, 'editLocation']);
    Route::delete('delete', [ApiLocationController::class, 'deleteLocation']);
    
});

