<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\HrManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HrManagerDashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $events = [];
        $authUser = HrManager::where('user_id', auth()->user()->id)->first();

        $appointments = Appointment::with(['applicant', 'hr_manager'])
        ->where('hr_manager_id', $authUser->id)
        ->get();

        foreach ($appointments as $appointment) {
            $events[] = [
                'title' => 'Interview with ' . $appointment->applicant->first_name,
                'start' => $appointment->start_time,
                'end' => $appointment->finish_time,
            ];
        }

        return Inertia::render('HRManagerDashboard', [
            'events' => $events,
        ]);
    }
}
