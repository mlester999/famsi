<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiApplicationController extends Controller
{
    public function application (Request $request) {
        $applicationValidate = $request->validate([
            'resume_path' => ['required', 'mimes:pdf', 'max:2048'],
            'applicant_id' => ['required'],
        ]);

        return response()->json(['success' => 'You successfully applied for this job. Please wait for the result of your application process. Thank you!'], 200);
    }


}
