<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;
use App\Models\User;
use App\Models\Applicant;
use Illuminate\Support\Facades\Auth;

class ApiApplicationController extends Controller
{
    public function application (Request $request) {
        $applicationValidate = $request->validate([
            'resume_path' => ['required', 'mimes:pdf', 'max:2048'],
            'applicant_id' => ['required'],
            'job_position_id' => ['required'],
        ]);

        $resumeFile = $request->file('resume_path');

        $fileName = $resumeFile->getClientOriginalName();

        $storedFilePath = $resumeFile->store('public');

        $path = storage_path('app/' . $storedFilePath);

        $cloudPath = Storage::disk('spaces')->putFileAs('uploads/applications/' . $applicationValidate['applicant_id'], $path, $fileName);

        $application = Application::create([
            'applicant_id' => $applicationValidate['applicant_id'],
            'job_position_id' => $applicationValidate['job_position_id'],
            'file_name' => $fileName,
            'file_path' => env('DO_URL') . '/' . $cloudPath,
            'status' => 1
        ]);

        unlink($path);

        $employeeIds = User::whereIn('user_type', [1, 2])->pluck('id')->toArray();

        $applicant = Applicant::where('id', $applicationValidate['applicant_id'])->first();

        $user = User::where('id', $applicant->user_id)->first();

        $notification = Notification::create([
            'title' => $applicant->first_name . ' ' . $applicant->last_name . ', a new applicant, has successfully applied for a job. Please take a look.',
            'user_id' => $employeeIds,
            'author_id' => $user->id,
            'status' => 0
        ]);

        return response()->json(['success' => 'You successfully applied for this job. Please wait for the result of your application process. Thank you!'], 200);
    }


}
