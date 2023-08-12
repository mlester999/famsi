<?php

use App\Http\Controllers\ActivityLogsController;
use App\Http\Controllers\HrManagerController;
use App\Http\Controllers\HrStaffController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\HrManagerDashboardController;
use App\Models\Applicant;
use App\Models\HrManager;
use App\Models\HrStaff;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->middleware('guest');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::group(['middleware' => 'role:applicant', 'prefix' => 'applicant', 'as' => 'applicant.'], function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');
    });

    Route::group(['middleware' => 'role:hr-staff', 'prefix' => 'hr-staff', 'as' => 'hr-staff.'], function () {
        Route::group(['prefix' => 'applicants', 'as' => 'applicants.'], function() {
            Route::get('/', [ApplicantController::class, 'index'])->name('index');

            Route::post('/store', [ApplicantController::class, 'store'])->name('store');

            Route::put('/update/{id}', [ApplicantController::class, 'update'])->name('update');

            Route::put('/activate/{id}', [ApplicantController::class, 'activate'])->name('activate');

            Route::put('/deactivate/{id}', [ApplicantController::class, 'deactivate'])->name('deactivate');
        });

        Route::get('/documents', function () {
            return Inertia::render('Documents');
        })->name('documents');
    });

    Route::group(['middleware' => 'role:hr-manager', 'prefix' => 'hr-manager', 'as' => 'hr-manager.'], function () {
        Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function() {
            Route::get('/', HrManagerDashboardController::class)->name('index');

            Route::post('/store', [HrManagerDashboardController::class, 'store'])->name('store');

            Route::put('/update/{id}', [HrManagerDashboardController::class, 'update'])->name('update');

            Route::delete('/delete/{id}', [HrManagerDashboardController::class, 'delete'])->name('delete');
        });

        Route::group(['prefix' => 'hr-staffs', 'as' => 'hr-staffs.'], function() {
            Route::get('/', [HrStaffController::class, 'index'])->name('index');

            Route::post('/store', [HrStaffController::class, 'store'])->name('store');

            Route::put('/update/{id}', [HrStaffController::class, 'update'])->name('update');

            Route::put('/activate/{id}', [HrStaffController::class, 'activate'])->name('activate');

            Route::put('/deactivate/{id}', [HrStaffController::class, 'deactivate'])->name('deactivate');
        });

        Route::group(['prefix' => 'applicants', 'as' => 'applicants.'], function() {
            Route::get('/', [ApplicantController::class, 'index'])->name('index');

            Route::post('/store', [ApplicantController::class, 'store'])->name('store');

            Route::put('/update/{id}', [ApplicantController::class, 'update'])->name('update');

            Route::put('/activate/{id}', [ApplicantController::class, 'activate'])->name('activate');

            Route::put('/deactivate/{id}', [ApplicantController::class, 'deactivate'])->name('deactivate');
        });

        Route::get('/documents', function () {
            return Inertia::render('Documents');
        })->name('documents');
    });

    Route::group(['middleware' => 'role:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function() {
            Route::get('/', function () {
                return Inertia::render('Dashboard', [
                    'totalApplicants' => Applicant::count(),
                    'totalHrStaffs' => HrStaff::count(),
                    'totalHrManagers' => HrManager::count()
                ]);
            })->name('index');
        });

        Route::get('/homepage', function () {
            return Inertia::render('HomePage');
        })->name('homepage');

        Route::get('/announcement', function () {
            return Inertia::render('Announcement');
        })->name('announcement');

            Route::group(['prefix' => 'activity-logs', 'as' => 'activity-logs.'], function () {
                Route::get('/', [ActivityLogsController::class, 'index'])->name('index');

                Route::delete('/destroy/{id}', [ActivityLogsController::class, 'destroy'])->name('destroy');
            });

            Route::group(['prefix' => 'hr-managers', 'as' => 'hr-managers.'], function () {
                Route::get('/', [HrManagerController::class, 'index'])->name('index');

                Route::post('/store', [HrManagerController::class, 'store'])->name('store');

                Route::put('/update/{id}', [HrManagerController::class, 'update'])->name('update');

                Route::put('/activate/{id}', [HrManagerController::class, 'activate'])->name('activate');

                Route::put('/deactivate/{id}', [HrManagerController::class, 'deactivate'])->name('deactivate');
            });

            Route::group(['prefix' => 'hr-staffs', 'as' => 'hr-staffs.'], function() {
                Route::get('/', [HrStaffController::class, 'index'])->name('index');

                Route::post('/store', [HrStaffController::class, 'store'])->name('store');

                Route::put('/update/{id}', [HrStaffController::class, 'update'])->name('update');

                Route::put('/activate/{id}', [HrStaffController::class, 'activate'])->name('activate');

                Route::put('/deactivate/{id}', [HrStaffController::class, 'deactivate'])->name('deactivate');
            });

            Route::group(['prefix' => 'applicants', 'as' => 'applicants.'], function() {
                Route::get('/', [ApplicantController::class, 'index'])->name('index');

                Route::post('/store', [ApplicantController::class, 'store'])->name('store');

                Route::put('/update/{id}', [ApplicantController::class, 'update'])->name('update');

                Route::put('/activate/{id}', [ApplicantController::class, 'activate'])->name('activate');

                Route::put('/deactivate/{id}', [ApplicantController::class, 'deactivate'])->name('deactivate');
            });

            Route::group(['prefix' => 'documents', 'as' => 'documents.'], function() {
                Route::get('/', [DocumentsController::class, 'index'])->name('index');

                Route::post('/upload', [DocumentsController::class, 'upload'])->name('upload');
            });

    });
});

require_once __DIR__ . '/jetstream.php';
