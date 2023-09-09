<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Applicant;
use App\Models\HrManager;
use App\Models\HrStaff;
use App\Models\EmployeeType;
use App\Models\JobType;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;

class EmployeeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = Request::only(['search']);
        $searchReq = Request::input('search');

        $jobTypes = JobType::all();

        $employeeTypes = EmployeeType::query()
        ->when($searchReq, function($query, $search) {
            $query->where(function ($query) use ($search) {
                    $query->whereRaw('LOWER(title) LIKE LOWER(?)', ['%' . $search . '%'])
                        ->orWhereRaw('LOWER(description) LIKE LOWER(?)', ['%' . $search . '%']);
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
        ->through(fn($employeeType) => [
            'id' => $employeeType->id,
            'job_type_id' => $employeeType->job_type_id,
            'title' => $employeeType->title,
            'description' => $employeeType->description,
            'created_at' => $employeeType->created_at,
        ]);

        if (empty($searchReq)) {
            unset($filters['search']);
        }

        $currentPage = $employeeTypes->currentPage();
        $lastPage = $employeeTypes->lastPage();
        $firstPage = 1;

        $previousPage = $currentPage - 1 > 0 ? $currentPage - 1 : null;
        $nextPage = $currentPage + 1 <= $lastPage ? $currentPage + 1 : null;

        $links = [];

        if ($previousPage !== null) {
            $links[] = [
                'url' => $employeeTypes->url($previousPage),
                'label' => 'Previous',
            ];
        }

        $links[] = [
            'url' => $employeeTypes->url(1),
            'label' => 1,
        ];

        if ($currentPage > 3) {
            $links[] = [
                'url' => $employeeTypes->url($currentPage - 1),
                'label' => '...',
            ];
        }

        $rangeStart = max(2, $currentPage - 1);
        $rangeEnd = min($lastPage - 1, $currentPage + 1);

        for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
            $links[] = [
                'url' => $employeeTypes->url($i),
                'label' => $i,
            ];
        }


        if ($currentPage < $lastPage - 2) {
            $links[] = [
                'url' => $employeeTypes->url($currentPage + 1),
                'label' => '...',
            ];
        }

        if ($firstPage !== $lastPage) {
            $links[] = [
                'url' => $employeeTypes->url($lastPage),
                'label' => $lastPage,
            ];
        }

        if ($nextPage !== null) {
            $links[] = [
                'url' => $employeeTypes->url($nextPage),
                'label' => 'Next',
            ];
        }

        return Inertia::render('EmployeeTypes', [
            'employeeTypes' => $employeeTypes,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $currentPage,
                'last_page' => $lastPage,
                'links' => $links,
            ],
            'jobTypes' => $jobTypes
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
        $employeeTypeValidate = Request::validate([
            'job_type_id' => ['required'],
            'title' => ['required', 'max:50'],
            'description' => ['required'],
        ]);

        EmployeeType::create([
            'job_type_id' => $employeeTypeValidate['job_type_id'],
            'title' => $employeeTypeValidate['title'],
            'description' => $employeeTypeValidate['description'],
        ]);

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeTypes $employeeTypes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeTypes $employeeTypes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $employeeTypeValidate = Request::validate([
            'job_type_id' => ['required'],
            'title' => ['required', 'max:50'],
            'description' => ['required'],
        ]);

        $employeeType = EmployeeType::findOrFail($id);

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

        if($employeeTypeValidate['job_type_id'] !== $employeeType->job_type_id) {
            activity()
            ->performedOn(EmployeeType::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Job Type's job_type_id from {$employeeType->job_type_id} to {$employeeTypeValidate['job_type_id']}");

            $employeeType->job_type_id = $employeeTypeValidate['job_type_id'];
        }

        if($employeeTypeValidate['title'] !== $employeeType->title) {
            activity()
            ->performedOn(EmployeeType::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Job Type's title from {$employeeType->title} to {$employeeTypeValidate['title']}");

            $employeeType->title = $employeeTypeValidate['title'];
        }

        if($employeeTypeValidate['description'] !== $employeeType->description) {
            activity()
            ->performedOn(EmployeeType::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Job Type's description from {$employeeType->description} to {$employeeTypeValidate['description']}");

            $employeeType->description = $employeeTypeValidate['description'];
        }

        $employeeType->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $employeeType = EmployeeType::findOrFail($id);

        $employeeType->delete();
    }
}
