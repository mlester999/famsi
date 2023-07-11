<?php

use App\Http\Controllers\HrManagerController;
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
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');
    });

    Route::group(['middleware' => 'role:hr-manager', 'prefix' => 'hr-manager', 'as' => 'hr-manager.'], function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');
    });

    Route::group(['middleware' => 'role:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::get('/homepage', function () {
            return Inertia::render('HomePage');
        })->name('homepage');

        Route::get('/announcement', function () {
            return Inertia::render('Announcement');
        })->name('announcement');

        Route::get('/hr-managers', [HrManagerController::class, 'index'])->name('hr-managers');

        Route::post('/hr-managers/store', [HrManagerController::class, 'store'])->name('hr-managers.store');

        Route::put('/hr-managers/update/{id}', [HrManagerController::class, 'update'])->name('hr-managers.update');

        Route::get('/hr-staffs', function () {
            return Inertia::render('HRStaffs');
        })->name('hr-staffs');

        Route::get('/applicants', function () {
            return Inertia::render('Applicants');
        })->name('applicants');

        Route::get('/files', function () {
            return Inertia::render('Files');
        })->name('files');
    });
});

require_once __DIR__ . '/jetstream.php';
