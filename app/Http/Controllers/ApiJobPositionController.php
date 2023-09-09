<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;

class ApiJobPositionController extends Controller
{
    public function details()
    {
        return response()->json(JobPosition::all());
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
                return response()->json(JobPosition::findOrFail($id));
            }

            return false;
        }
            return false;
    }
}
