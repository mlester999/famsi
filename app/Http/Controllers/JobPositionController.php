<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Applicant;
use App\Models\CompanyAssignment;
use App\Models\HrManager;
use App\Models\HrStaff;
use App\Models\JobPosition;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;

class JobPositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = Request::only(['search']);
        $searchReq = Request::input('search');

        $companyAssignments = CompanyAssignment::all();

        $jobPositions = JobPosition::query()
        ->when($searchReq, function($query, $search) {
            $query->where(function ($query) use ($search) {
                    $query->whereRaw('LOWER(title) LIKE LOWER(?)', ['%' . $search . '%'])
                        ->orWhereRaw('LOWER(location) LIKE LOWER(?)', ['%' . $search . '%'])
                        ->orWhereRaw('LOWER(job_type) LIKE LOWER(?)', ['%' . $search . '%'])
                        ->orWhereRaw('LOWER(employment_type) LIKE LOWER(?)', ['%' . $search . '%'])
                        ->orWhereRaw('LOWER(schedule) LIKE LOWER(?)', ['%' . $search . '%']);
            });

            // Use this if we like to perform a case-insensitive and approximate matching search

            // $query->where(function ($query) use ($search) {
            //     $lowerSearch = strtolower($search);
            //     $query->whereRaw('LOWER(first_name) LIKE LOWER(?)', ['%' . $search . '%'])
            //           ->orWhereRaw('SOUNDEX(last_name) = SOUNDEX(?)', [$lowerSearch]);
            // })
            // ->orWhere(function ($query) use ($search) {
            //     $lowerSearch = strtolower($search);
            //     $query->whereRaw('LOWER(last_name) LIKE LOWER(?)', ['%' . $search . '%'])
            //           ->orWhereRaw('SOUNDEX(first_name) = SOUNDEX(?)', [$lowerSearch]);
            // });
        })
        ->orderBy('id', 'asc')
        ->paginate(10)
        ->withQueryString()
        ->through(fn($jobPosition) => [
            'id' => $jobPosition->id,
            'title' => $jobPosition->title,
            'description' => $jobPosition->description,
            'company_profile' => $jobPosition->company_profile,
            'location' => $jobPosition->location,
            'job_type' => $jobPosition->job_type,
            'employment_type' => $jobPosition->employment_type,
            'schedule' => $jobPosition->schedule,
            'created_at' => $jobPosition->created_at,
        ]);

        if (empty($searchReq)) {
            unset($filters['search']);
        }

        $currentPage = $jobPositions->currentPage();
        $lastPage = $jobPositions->lastPage();
        $firstPage = 1;

        $previousPage = $currentPage - 1 > 0 ? $currentPage - 1 : null;
        $nextPage = $currentPage + 1 <= $lastPage ? $currentPage + 1 : null;

        $links = [];

        if ($previousPage !== null) {
            $links[] = [
                'url' => $jobPositions->url($previousPage),
                'label' => 'Previous',
            ];
        }

        $links[] = [
            'url' => $jobPositions->url(1),
            'label' => 1,
        ];

        if ($currentPage > 3) {
            $links[] = [
                'url' => $jobPositions->url($currentPage - 1),
                'label' => '...',
            ];
        }

        $rangeStart = max(2, $currentPage - 1);
        $rangeEnd = min($lastPage - 1, $currentPage + 1);

        for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
            $links[] = [
                'url' => $jobPositions->url($i),
                'label' => $i,
            ];
        }


        if ($currentPage < $lastPage - 2) {
            $links[] = [
                'url' => $jobPositions->url($currentPage + 1),
                'label' => '...',
            ];
        }

        if ($firstPage !== $lastPage) {
            $links[] = [
                'url' => $jobPositions->url($lastPage),
                'label' => $lastPage,
            ];
        }

        if ($nextPage !== null) {
            $links[] = [
                'url' => $jobPositions->url($nextPage),
                'label' => 'Next',
            ];
        }

        return Inertia::render('JobPositions', [
            'jobPositions' => $jobPositions,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $currentPage,
                'last_page' => $lastPage,
                'links' => $links,
            ],
            'companyAssignments' => $companyAssignments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $jobPositionValidate = Request::validate([
            'title' => ['required', 'max:50'],
            'description' => ['required'],
            'company_profile' => ['required'],
            'location' => ['required', 'max:50'],
            'job_type' => ['required', 'max:50'],
            'employment_type' => ['required', 'max:50'],
            'schedule' => ['required'],
        ]);

        $user = JobPosition::create([
            'title' => $jobPositionValidate['title'],
            'description' => $jobPositionValidate['description'],
            'company_profile' => $jobPositionValidate['company_profile'],
            'location' => $jobPositionValidate['location'],
            'job_type' => $jobPositionValidate['job_type'],
            'employment_type' => $jobPositionValidate['employment_type'],
            'schedule' => $jobPositionValidate['schedule'],
        ]);

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     */
    public function show(JobPositions $jobPositions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobPositions $jobPositions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $jobPositionValidate = Request::validate([
            'title' => ['required', 'max:50'],
            'description' => ['required'],
            'company_profile' => ['required'],
            'location' => ['required', 'max:50'],
            'job_type' => ['required', 'max:50'],
            'employment_type' => ['required', 'max:50'],
            'schedule' => ['required'],
        ]);

        $jobPosition = JobPosition::findOrFail($id);

        $userInfo = null;
        $userRole = '';

        if(auth()->user()->user_type === User::ADMIN) {
            $userInfo = Admin::where('user_id', auth()->user()->id)->first();
            $userRole = "Admin";
        } else if(auth()->user()->user_type === User::HR_MANAGER) {
            $userInfo = HrManager::where('user_id', auth()->user()->id)->first();
            $userRole = "Hr Manager";
        } else if(auth()->user()->user_type === User::HR_STAFF) {
            $userInfo = HrStaff::where('user_id', auth()->user()->id)->first();
            $userRole = "Hr Staff";
        } else if(auth()->user()->user_type === User::APPLICANT) {
            $userInfo = Applicant::where('user_id', auth()->user()->id)->first();
            $userRole = "Applicant";
        }

        if($jobPositionValidate['title'] !== $jobPosition->title) {
            activity()
            ->performedOn(JobPosition::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Job Position's title from {$jobPosition->title} to {$jobPositionValidate['title']}");

            $jobPosition->title = $jobPositionValidate['title'];
        }

        if($jobPositionValidate['description'] !== $jobPosition->description) {
            activity()
            ->performedOn(JobPosition::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Job Position's description from {$jobPosition->description} to {$jobPositionValidate['description']}");

            $jobPosition->description = $jobPositionValidate['description'];
        }

        if($jobPositionValidate['company_profile'] !== $jobPosition->company_profile) {
            activity()
            ->performedOn(JobPosition::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Job Position's Company Profile");

            $jobPosition->company_profile = $jobPositionValidate['company_profile'];
        }

        if($jobPositionValidate['location'] !== $jobPosition->location) {
            activity()
            ->performedOn(JobPosition::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Job Position's location from {$jobPosition->location} to {$jobPositionValidate['location']}");

            $jobPosition->location = $jobPositionValidate['location'];
        }

        if($jobPositionValidate['job_type'] !== $jobPosition->job_type) {
            activity()
            ->performedOn(JobPosition::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Job Position's job_type from {$jobPosition->job_type} to {$jobPositionValidate['job_type']}");

            $jobPosition->job_type = $jobPositionValidate['job_type'];
        }

        if($jobPositionValidate['employment_type'] !== $jobPosition->employment_type) {
            activity()
            ->performedOn(JobPosition::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Job Position's employment_type from {$jobPosition->employment_type} to {$jobPositionValidate['employment_type']}");

            $jobPosition->employment_type = $jobPositionValidate['employment_type'];
        }

        if($jobPositionValidate['schedule'] !== $jobPosition->schedule) {
            activity()
            ->performedOn(JobPosition::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Job Position's schedule from {$jobPosition->schedule} to {$jobPositionValidate['schedule']}");

            $jobPosition->schedule = $jobPositionValidate['schedule'];
        }

        $jobPosition->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jobPosition = JobPosition::findOrFail($id);

        $jobPosition->delete();
    }
}
