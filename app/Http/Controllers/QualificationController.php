<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Applicant;
use App\Models\HrManager;
use App\Models\HrStaff;
use App\Models\Qualification;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;

class QualificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = Request::only(['search']);
        $searchReq = Request::input('search');

        $qualifications = Qualification::query()
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
        ->through(fn($qualification) => [
            'id' => $qualification->id,
            'title' => $qualification->title,
            'description' => $qualification->description,
            'is_active' => $qualification->is_active,
            'created_at' => $qualification->created_at,
        ]);

        if (empty($searchReq)) {
            unset($filters['search']);
        }

        $currentPage = $qualifications->currentPage();
        $lastPage = $qualifications->lastPage();
        $firstPage = 1;

        $previousPage = $currentPage - 1 > 0 ? $currentPage - 1 : null;
        $nextPage = $currentPage + 1 <= $lastPage ? $currentPage + 1 : null;

        $links = [];

        if ($previousPage !== null) {
            $links[] = [
                'url' => $qualifications->url($previousPage),
                'label' => 'Previous',
            ];
        }

        $links[] = [
            'url' => $qualifications->url(1),
            'label' => 1,
        ];

        if ($currentPage > 3) {
            $links[] = [
                'url' => $qualifications->url($currentPage - 1),
                'label' => '...',
            ];
        }

        $rangeStart = max(2, $currentPage - 1);
        $rangeEnd = min($lastPage - 1, $currentPage + 1);

        for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
            $links[] = [
                'url' => $qualifications->url($i),
                'label' => $i,
            ];
        }


        if ($currentPage < $lastPage - 2) {
            $links[] = [
                'url' => $qualifications->url($currentPage + 1),
                'label' => '...',
            ];
        }

        if ($firstPage !== $lastPage) {
            $links[] = [
                'url' => $qualifications->url($lastPage),
                'label' => $lastPage,
            ];
        }

        if ($nextPage !== null) {
            $links[] = [
                'url' => $qualifications->url($nextPage),
                'label' => 'Next',
            ];
        }

        return Inertia::render('Qualifications', [
            'qualifications' => $qualifications,
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
        $qualificationValidate = Request::validate([
            'title' => ['required', 'max:50'],
            'description' => ['required'],
        ]);

        $user = Qualification::create([
            'title' => $qualificationValidate['title'],
            'description' => $qualificationValidate['description'],
        ]);

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     */
    public function show(Qualifications $qualifications)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Qualifications $qualifications)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $qualificationValidate = Request::validate([
            'title' => ['required'],
            'description' => ['required'],
        ]);

        $qualification = Qualification::findOrFail($id);

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

        if($qualificationValidate['title'] !== $qualification->title) {
            activity()
            ->performedOn(Qualification::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Qualification's title from {$qualification->title} to {$qualificationValidate['title']}");

            $qualification->title = $qualificationValidate['title'];
        }

        if($qualificationValidate['description'] !== $qualification->description) {
            activity()
            ->performedOn(Qualification::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Qualification's description from {$qualification->description} to {$qualificationValidate['description']}");

            $qualification->description = $qualificationValidate['description'];
        }

        $qualification->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $qualification = Qualification::findOrFail($id);

        $qualification->delete();
    }

/**
     * Activate the specified resource.
     */
    public function activate($id)
    {
        $qualification = Qualification::findOrFail($id);

        $qualification->is_active = 1;

        $qualification->save();
    }

    /**
     * Deactivate the specified resource.
     */
    public function deactivate($id)
    {
        $qualification = Qualification::findOrFail($id);

        $qualification->is_active = 0;

        $qualification->save();
    }
}
