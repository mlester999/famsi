<?php

namespace App\Http\Controllers;

use App\Models\HrManager;
use App\Http\Requests\StoreHrManagerRequest;
use App\Http\Requests\UpdateHrManagerRequest;
use App\Models\User;
use Inertia\Inertia;

class HrManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $hrManagers = HrManager::with('user')->paginate(10);
        $currentPage = $hrManagers->currentPage();
        $lastPage = $hrManagers->lastPage();

        $previousPage = $currentPage - 1 > 0 ? $currentPage - 1 : null;
        $nextPage = $currentPage + 1 <= $lastPage ? $currentPage + 1 : null;

        $links = [];

        if ($previousPage !== null) {
            $links[] = [
                'url' => $hrManagers->url($previousPage),
                'label' => 'Previous',
            ];
        }

        $links[] = [
            'url' => $hrManagers->url(1),
            'label' => 1,
        ];

        if ($currentPage > 3) {
            $links[] = [
                'url' => $hrManagers->url($currentPage - 1),
                'label' => '...',
            ];
        }

        $rangeStart = max(2, $currentPage - 1);
        $rangeEnd = min($lastPage - 1, $currentPage + 1);

        for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
            $links[] = [
                'url' => $hrManagers->url($i),
                'label' => $i,
            ];
        }

        if ($currentPage < $lastPage - 2) {
            $links[] = [
                'url' => $hrManagers->url($currentPage + 1),
                'label' => '...',
            ];
        }

        $links[] = [
            'url' => $hrManagers->url($lastPage),
            'label' => $lastPage,
        ];

        if ($nextPage !== null) {
            $links[] = [
                'url' => $hrManagers->url($nextPage),
                'label' => 'Next',
            ];
        }


        return Inertia::render('HRManagers', [
            'hrManagers' => $hrManagers,
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
    public function store(StoreHrManagerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(HrManager $hrManager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HrManager $hrManager)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHrManagerRequest $request, HrManager $hrManager)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HrManager $hrManager)
    {
        //
    }
}
