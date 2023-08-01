<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HrManagerDashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $events = [];

        $appointments = Appointment::with(['applicant', 'hr_manager'])->get();

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
