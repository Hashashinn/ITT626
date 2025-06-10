<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\FingerDevicesControlller;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::get('attended/{user_id}', '\App\Http\Controllers\AttendanceController@attended')->name('attended');
Route::get('attended-before/{user_id}', '\App\Http\Controllers\AttendanceController@attendedBefore')->name('attendedBefore');
Auth::routes(['register' => false, 'reset' => false]);

Route::prefix('employees')->group(function () {
    Route::get('/department/{departmentId}', [AdminController::class, 'getByDepartment'])->name('employees.byDepartment');
    Route::get('/location/{locationId}', [AdminController::class, 'getByLocation'])->name('employees.byLocation');
});

Route::group(['middleware' => ['auth', 'auto_logout']], function () {
    // Route::resource('/employees', '\App\Http\Controllers\EmployeeController');
    Route::resource('/employees', '\App\Http\Controllers\EmployeeController');
    Route::get('/attendance', '\App\Http\Controllers\AttendanceController@index')->name('attendance');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');


    Route::controller(DepartmentController::class)->prefix('department')->group(function () {
        Route::get('/getDepartmentById/{id}', 'getDepartmentById');
        Route::post('/update/{id}', 'update');
        Route::post('/store', 'store');
        Route::get('/delete/{id}', 'delete');
    });

    Route::controller(AttendanceController::class)->prefix('attendance')->group(function () {
        Route::get('/getAttendanceById/{id}', 'getAttendanceById');
        Route::get('/update/{id}', 'update');
        Route::post('/store', 'store');
        Route::post('/punchOut/{id}', 'punchOut');
        Route::get('/delete/{id}', 'delete');
        Route::get('/attendanceList/{id}', 'attendanceList');
        Route::get('/getTodayAttendanceById', 'getTodayAttendanceById');
    });

    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    // Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    Route::get('/latetime', '\App\Http\Controllers\AttendanceController@indexLatetime')->name('indexLatetime');
    Route::get('/leave', '\App\Http\Controllers\LeaveController@index')->name('leave');
    Route::get('/overtime', '\App\Http\Controllers\LeaveController@indexOvertime')->name('indexOvertime');

    Route::get('/admin', '\App\Http\Controllers\AdminController@index')->name('admin');
    Route::get('/employee', '\App\Http\Controllers\AdminController@index')->name('employee');

    Route::resource('/schedule', '\App\Http\Controllers\ScheduleController');

    Route::get('/check', '\App\Http\Controllers\CheckController@index')->name('check');
    Route::get('/sheet-report', '\App\Http\Controllers\CheckController@sheetReport')->name('sheet-report');
    Route::post('check-store', '\App\Http\Controllers\CheckController@CheckStore')->name('check_store');

    // Fingerprint Devices
    Route::resource('/finger_device', '\App\Http\Controllers\BiometricDeviceController');


    Route::controller(AdminController::class)->group(function () {
        Route::post('/uploadExcel/{type}', 'uploadExcel');
        Route::get('/changePasswordView', 'changePasswordView');
        Route::post('/changePassword', 'changePassword');

        Route::prefix('role')->group(function () {
            Route::get('/getRoleById/{id}', 'getRoleById');
            Route::post('/update/{id}', 'update');
            Route::post('/store', 'store');
            Route::get('/delete/{id}', 'delete');
            Route::get('/roleView', 'roleView');
        });

        Route::prefix('location')->group(function () {
            Route::get('/getLocationById/{id}', 'getLocationById');
            Route::post('/locationUpdate/{id}', 'locationUpdate');
            Route::post('/locationStore', 'locationStore');
            Route::get('/locationDelete/{id}', 'locationDelete');
            Route::get('/locationView', 'locationView');
        });

        Route::prefix('department')->group(function () {
            Route::post('/departmentStore', 'departmentStore');
            Route::post('/departmentUpdate/{id}',  'departmentUpdate');
            Route::get('/departmentDelete/{id}',  'departmentDelete');
            Route::get('/departmentView', 'departmentView');
        });
        // Route::post('/department/departmentStore', [DepartmentController::class, 'departmentStore'])->name('department.store');
        // Route::post('/department/departmentUpdate/{id}', [DepartmentController::class, 'departmentUpdate'])->name('department.update');
        // Route::get('/department/departmentDelete/{id}', [DepartmentController::class, 'departmentDelete'])->name('department.delete');
        // Route::get('/department/departmentView', [DepartmentController::class, 'index'])->name('department.view');
    });

    Route::controller(EmployeeController::class)->group(function () {
        Route::get('/employeHistoryView', 'employeHistoryView');
        Route::post('/historySearch', 'historySearch');
        Route::post('/employee/search', 'searchEmployee');

        Route::prefix('employee')->group(function () {
            Route::get('/getemployeeById/{id}', 'getemployeeById');
            Route::post('/employeeUpdate/{id}', 'employeeUpdate');
            Route::post('/employeeStore', 'employeeStore');
            Route::get('/employeeDelete/{id}', 'employeeDelete');
            Route::get('/employeeView', 'employeeView');
        });
    });

    Route::controller(AttendanceController::class)->group(function () {
        Route::get('/captureView', 'captureView');
        Route::get('/captureOutsideView', 'captureOutsideView');
        Route::post('/storeActivityUser', 'storeActivityUser');
        Route::get('/getActivityOutsideByUserId/{id}', 'getActivityOutsideByUserId');
        Route::get('/getActivityOutsideByAttId/{id}', 'getActivityOutsideByAttId');
        Route::get('/timeOffView', 'timeOffView');
        Route::post('/storeTimeoff', 'storeTimeoff');
        Route::post('/updateTimeoff/{id}', 'updateTimeoff');
        Route::get('/timeoffListView', 'timeoffListView');
        Route::post('/timeoff/search', 'searchTimeoff');
        Route::match(['get', 'post'], '/attendance/search', 'searchAttendanceLog');
        Route::post('/attendanceStore', 'attendanceStore');
        Route::get('/download-pdf', 'downloadPDF');
        Route::get('/download-csv','downloadCSV');
    });

    Route::get('/attendanceData', [ApiController::class, 'attendanceData']);
    Route::post('/searchAttendanceLog', [ApiController::class, 'attendanceData']);

    Route::controller(ScheduleController::class)->group(function () {
        Route::get('/getScheduleById/{id}', 'getScheduleById');
        Route::post('/scheduleUpdate/{id}', 'scheduleUpdate');
        Route::post('/scheduleStore', 'scheduleStore');
        Route::get('/scheduleDelete/{id}', 'scheduleDelete');
    });


    Route::controller(RolesController::class)->group(function () {
        Route::get('/importRoles', 'import');
        Route::get('/importDepartment', 'importDepartment');
        Route::get('/importLocation', 'importLocation');
        Route::get('/importUser', 'importUser');
    });
});


Route::controller(AdminController::class)->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('/registerView', 'registerView');
        // Route::post('/update/{id}', 'update');
        Route::post('/registerStore', 'registerStore');
        // Route::get('/delete/{id}', 'delete');
        // Route::get('/roleView', 'roleView');
    });
});

Route::controller(LoginController::class)->group(function () {
    Route::get('/logout', 'logout');
    Route::get('/requestResetPassword', 'requestResetPassword');
    Route::post('/updateResetPassword/{resetPassToken}', 'updateResetPassword');
    Route::get('/resetPasswordView/{token}', 'resetPasswordView');
    Route::get('/login', 'loginView')->name('login');
    Route::post('/heartbeat', 'heartbeat');

    Route::prefix('auth')->group(function () {
        Route::post('/loginProcess', 'loginProcess');
        Route::post('/sendResetPasswordEmail', 'sendResetPasswordEmail');
        Route::get('/loginView', 'loginView');
        // Route::post('/update/{id}', 'update');
        // Route::post('/registerStore', 'registerStore');
        // Route::get('/delete/{id}', 'delete');
        // Route::get('/roleView', 'roleView');
    });
});
Route::get('/transferFile', '\App\Http\Controllers\ApiController@transferFile');

// Password reset routes
// Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
// Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

//Time-OFF
// Route::get('/time-off', [TimeOffController::class, 'index'])->name('timeoff.index');
// Route::post('/time-off', [TimeOffController::class, 'store'])->name('timeoff.store');




Route::group(['middleware' => ['auth']], function () {

    // Route::get('/home', 'HomeController@index')->name('home');





});
