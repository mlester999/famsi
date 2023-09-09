<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;

class ApiJobPositionController extends Controller
{
    public function details()
    {
        return response()->json(JobPosition::with(['jobType', 'employeeType', 'industry'])->get());
    }

    public function findOne($id, $slug)
    {
        if($id && $slug) {
            $jobPosition = JobPosition::findOrFail($id);

            // Convert to lowercase
            $jobTitle = strtolower($jobPosition->title);

            // Replace spaces with hyphens
            $jobTitle = str_replace(' ', '-', $slug);

            // Remove periods
            $jobTitle = str_replace('.', '', $slug);

            // Replace slashes with hyphens
            $jobTitle = str_replace('/', '-', $slug);

            if($jobTitle === $slug) {
                return response()->json(JobPosition::with(['jobType', 'employeeType', 'industry'])->findOrFail($id));
            }

            return false;
        }
            return false;
    }

    public function findRelatedJobs($id)
    {
        if($id) {
            $jobPosition = JobPosition::with(['jobType', 'employeeType', 'industry'])->findOrFail($id);

            $relatedJobs = JobPosition::where('industry_id', $jobPosition->industry_id)
            ->with(['jobType', 'employeeType', 'industry'])
            ->whereNotIn('id', [$id])
            ->take(5)
            ->get();

            if (!$relatedJobs->isEmpty()) {
                return response()->json($relatedJobs);
            }
            return false;
        }

        return false;
    }
}
