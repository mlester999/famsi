<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $cloudPath = Storage::disk('spaces')->putFileAs('uploads/applications/' . $applicationValidate['applicant_id'] . '/', $path, $fileName);

        $user = Application::create([
            'applicant_id' => $applicationValidate['applicant_id'],
            'job_position_id' => $applicationValidate['job_position_id'],
            'file_name' => $fileName,
            'file_path' => env('DO_URL') . '/' . $cloudPath,
            'status' => 1
        ]);

        unlink($path);

        return response()->json(['success' => 'You successfully applied for this job. Please wait for the result of your application process. Thank you!'], 200);
    }


}
