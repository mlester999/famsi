<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Applicant;
use App\Models\HrManager;
use App\Models\HrStaff;
use App\Models\Industry;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;

class IndustryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = Request::only(['search']);
        $searchReq = Request::input('search');

        $industries = Industry::query()
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
        ->through(fn($industry) => [
            'id' => $industry->id,
            'title' => $industry->title,
            'description' => $industry->description,
            'is_active' => $industry->is_active,
            'created_at' => $industry->created_at,
        ]);

        if (empty($searchReq)) {
            unset($filters['search']);
        }

        $currentPage = $industries->currentPage();
        $lastPage = $industries->lastPage();
        $firstPage = 1;

        $previousPage = $currentPage - 1 > 0 ? $currentPage - 1 : null;
        $nextPage = $currentPage + 1 <= $lastPage ? $currentPage + 1 : null;

        $links = [];

        if ($previousPage !== null) {
            $links[] = [
                'url' => $industries->url($previousPage),
                'label' => 'Previous',
            ];
        }

        $links[] = [
            'url' => $industries->url(1),
            'label' => 1,
        ];

        if ($currentPage > 3) {
            $links[] = [
                'url' => $industries->url($currentPage - 1),
                'label' => '...',
            ];
        }

        $rangeStart = max(2, $currentPage - 1);
        $rangeEnd = min($lastPage - 1, $currentPage + 1);

        for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
            $links[] = [
                'url' => $industries->url($i),
                'label' => $i,
            ];
        }


        if ($currentPage < $lastPage - 2) {
            $links[] = [
                'url' => $industries->url($currentPage + 1),
                'label' => '...',
            ];
        }

        if ($firstPage !== $lastPage) {
            $links[] = [
                'url' => $industries->url($lastPage),
                'label' => $lastPage,
            ];
        }

        if ($nextPage !== null) {
            $links[] = [
                'url' => $industries->url($nextPage),
                'label' => 'Next',
            ];
        }

        return Inertia::render('Industries', [
            'industries' => $industries,
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
        $industryValidate = Request::validate([
            'title' => ['required', 'max:50'],
            'description' => ['required'],
        ]);

        Industry::create([
            'title' => $industryValidate['title'],
            'description' => $industryValidate['description'],
        ]);

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     */
    public function show(Industries $industries)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Industries $industries)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $industryValidate = Request::validate([
            'title' => ['required'],
            'description' => ['required'],
        ]);

        $industry = Industry::findOrFail($id);

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

        if($industryValidate['title'] !== $industry->title) {
            activity()
            ->performedOn(Industry::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Industry's title from {$industry->title} to {$industryValidate['title']}");

            $industry->title = $industryValidate['title'];
        }

        if($industryValidate['description'] !== $industry->description) {
            activity()
            ->performedOn(Industry::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Industry's description from {$industry->description} to {$industryValidate['description']}");

            $industry->description = $industryValidate['description'];
        }

        $industry->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $industry = Industry::findOrFail($id);

        $industry->delete();
    }

          /**
     * Activate the specified resource.
     */
    public function activate($id)
    {
        $industry = Industry::findOrFail($id);

        $industry->is_active = 1;

        $industry->save();
    }

    /**
     * Deactivate the specified resource.
     */
    public function deactivate($id)
    {
        $industry = Industry::findOrFail($id);

        $industry->is_active = 0;

        $industry->save();
    }
}
