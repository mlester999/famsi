<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;

class ApiJobPositionController extends Controller
{
    public function details()
    {
        return response()->json(JobPosition::all());
    }
}
