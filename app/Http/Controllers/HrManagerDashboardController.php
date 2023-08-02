<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Appointment;
use App\Models\HrManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
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
        $applicants = Applicant::all();

        $appointments = Appointment::with(['applicant', 'hr_manager'])
        ->where('hr_manager_id', $authUser->id)
        ->get();


        foreach ($appointments as $appointment) {
            $events[] = [
                'id' => $appointment->id,
                'title' => 'Interview with ' . $appointment->applicant->first_name,
                'start' => $appointment->start_time,
                'end' => $appointment->finish_time,
            ];
        }

        return Inertia::render('HRManagerDashboard', [
            'events' => $events,
            'applicants' => $applicants
        ]);
    }

    public function store()
    {
        $scheduleValidate = Request::validate([
            'startTimeDate' => ['required'],
            'endTimeDate' => ['required'],
            'title' => ['required', 'max:50'],
            'applicant' => ['required']
        ]);


        $authUser = HrManager::where('user_id', auth()->user()->id)->first();

        $startDateTime = Carbon::createFromFormat('Y-m-d\TH:i:sP', $scheduleValidate['startTimeDate']);
        $startDateTime->setTimezone('Asia/Manila');

        $endDateTime = Carbon::createFromFormat('Y-m-d\TH:i:sP', $scheduleValidate['endTimeDate']);
        $endDateTime->setTimezone('Asia/Manila');

        $schedule = Appointment::create([
            'start_time' => $startDateTime,
            'finish_time' => $endDateTime,
            'comments' => $scheduleValidate['title'],
            'applicant_id' => $scheduleValidate['applicant'],
            'hr_manager_id' => $authUser->id
        ]);

        $schedule->save();

        return back()->with('success', 'Action performed successfully.');
    }

    public function delete($id)
    {
        $appointment = Appointment::find($id);

        $appointment->delete();

        return back()->with('success', 'Action performed successfully.');
    }
}
