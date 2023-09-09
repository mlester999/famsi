<?php

use App\Http\Controllers\ApiJobPositionController;
use App\Http\Controllers\AuthApplicantsController;
use App\Models\JobPosition;
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
    $user = $request->user()->load('applicant'); // Eager load the 'applicant' relationship
    return response()->json($user);
});

// Route::middleware('auth:sanctum')->get('/job-positions/details', function (Request $request) {
//     $jobPositionDetails = JobPosition::all();
//     return response()->json($jobPositionDetails);
// });

// Authenticating and signing up user
Route::post('login', [AuthApplicantsController::class, 'login']);
Route::post('register', [AuthApplicantsController::class, 'register']);
Route::post('logout', [AuthApplicantsController::class, 'logout']);
Route::post('refresh', [AuthApplicantsController::class, 'refresh']);
Route::get('details', [AuthApplicantsController::class, 'details']);

Route::get('job-positions/details', [ApiJobPositionController::class, 'details']);
Route::get('job-positions/details/{id}/{slug}', [ApiJobPositionController::class, 'findOne']);
Route::get('job-positions/related-jobs/{id}', [ApiJobPositionController::class, 'findRelatedJobs']);


