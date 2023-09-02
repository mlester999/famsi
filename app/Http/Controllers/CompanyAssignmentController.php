<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Applicant;
use App\Models\HrManager;
use App\Models\HrStaff;
use App\Models\CompanyAssignment;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;

class CompanyAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = Request::only(['search']);
        $searchReq = Request::input('search');

        $companyAssignments = CompanyAssignment::query()
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
        ->through(fn($companyAssignment) => [
            'id' => $companyAssignment->id,
            'title' => $companyAssignment->title,
            'description' => $companyAssignment->description,
            'created_at' => $companyAssignment->created_at,
        ]);

        if (empty($searchReq)) {
            unset($filters['search']);
        }

        $currentPage = $companyAssignments->currentPage();
        $lastPage = $companyAssignments->lastPage();
        $firstPage = 1;

        $previousPage = $currentPage - 1 > 0 ? $currentPage - 1 : null;
        $nextPage = $currentPage + 1 <= $lastPage ? $currentPage + 1 : null;

        $links = [];

        if ($previousPage !== null) {
            $links[] = [
                'url' => $companyAssignments->url($previousPage),
                'label' => 'Previous',
            ];
        }

        $links[] = [
            'url' => $companyAssignments->url(1),
            'label' => 1,
        ];

        if ($currentPage > 3) {
            $links[] = [
                'url' => $companyAssignments->url($currentPage - 1),
                'label' => '...',
            ];
        }

        $rangeStart = max(2, $currentPage - 1);
        $rangeEnd = min($lastPage - 1, $currentPage + 1);

        for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
            $links[] = [
                'url' => $companyAssignments->url($i),
                'label' => $i,
            ];
        }


        if ($currentPage < $lastPage - 2) {
            $links[] = [
                'url' => $companyAssignments->url($currentPage + 1),
                'label' => '...',
            ];
        }

        if ($firstPage !== $lastPage) {
            $links[] = [
                'url' => $companyAssignments->url($lastPage),
                'label' => $lastPage,
            ];
        }

        if ($nextPage !== null) {
            $links[] = [
                'url' => $companyAssignments->url($nextPage),
                'label' => 'Next',
            ];
        }

        return Inertia::render('CompanyAssignments', [
            'companyAssignments' => $companyAssignments,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $currentPage,
                'last_page' => $lastPage,
                'links' => $links,
            ],
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
        $companyAssignmentValidate = Request::validate([
            'title' => ['required', 'max:50'],
            'description' => ['required'],
        ]);

        $user = CompanyAssignment::create([
            'title' => $companyAssignmentValidate['title'],
            'description' => $companyAssignmentValidate['description'],
        ]);

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyAssignments $companyAssignments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyAssignments $companyAssignments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $companyAssignmentValidate = Request::validate([
            'title' => ['required'],
            'description' => ['required'],
        ]);

        $companyAssignment = CompanyAssignment::findOrFail($id);

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

        if($companyAssignmentValidate['title'] !== $companyAssignment->title) {
            activity()
            ->performedOn(CompanyAssignment::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Company Assignment's title from {$companyAssignment->title} to {$companyAssignmentValidate['title']}");

            $companyAssignment->title = $companyAssignmentValidate['title'];
        }

        if($companyAssignmentValidate['description'] !== $companyAssignment->description) {
            activity()
            ->performedOn(CompanyAssignment::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Company Assignment's description from {$companyAssignment->description} to {$companyAssignmentValidate['description']}");

            $companyAssignment->description = $companyAssignmentValidate['description'];
        }

        $companyAssignment->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $companyAssignment = CompanyAssignment::findOrFail($id);

        $companyAssignment->delete();
    }
}
