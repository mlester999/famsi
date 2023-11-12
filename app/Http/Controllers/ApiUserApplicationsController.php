<?php

namespace App\Http\Controllers;

use App\Models\Application;

class ApiUserApplicationsController extends Controller
{
    public function myApplications($id)
    {
        return response()->json(Application::where(['applicant_id' => $id])->with(['applicant', 'jobPosition', 'jobPosition.jobType', 'jobPosition.employeeType', 'jobPosition.industry'])->get());
    }
}
