<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use App\Models\CompanyAssignment;
use App\Models\EmployeeType;
use App\Models\Industry;
use App\Models\JobType;
use App\Models\Qualification;

class ApiJobRelatedController extends Controller
{
    public function qualifications () {
        return Qualification::all();
    }

    public function benefits() {
        return Benefit::all();
    }

    public function companyAssignments() {
        return CompanyAssignment::all();
    }

    public function jobTypes() {
        return JobType::all();
    }

    public function employmentTypes() {
        return EmployeeType::all();
    }

    public function industries() {
        return Industry::all();
    }
}
