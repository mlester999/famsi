<?php

namespace App\Http\Controllers;

use App\Models\HrManager;
use App\Http\Requests\StoreHrManagerRequest;
use App\Http\Requests\UpdateHrManagerRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class HrManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $filters = Request::only(['search']);
        $searchReq = Request::input('search');

        $hrManagers = HrManager::query()
        ->with('user')
        ->when($searchReq, function($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(first_name) LIKE LOWER(?)', ['%' . $search . '%'])
                      ->orWhereRaw('LOWER(last_name) LIKE LOWER(?)', ['%' . $search . '%']);
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
        ->through(fn($hrManager) => [
            'id' => $hrManager->id,
            'first_name' => $hrManager->first_name,
            'middle_name' => $hrManager->middle_name,
            'last_name' => $hrManager->last_name,
            'gender' => $hrManager->gender,
            'email' => $hrManager->user->email,
            'contact_number' => $hrManager->contact_number,
            'created_at' => $hrManager->created_at,
        ]);

        if (empty($searchReq)) {
            unset($filters['search']);
        }

        $currentPage = $hrManagers->currentPage();
        $lastPage = $hrManagers->lastPage();
        $firstPage = 1;

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

        if ($firstPage !== $lastPage) {
            $links[] = [
                'url' => $hrManagers->url($lastPage),
                'label' => $lastPage,
            ];
        }

        if ($nextPage !== null) {
            $links[] = [
                'url' => $hrManagers->url($nextPage),
                'label' => 'Next',
            ];
        }


        return Inertia::render('HRManagers', [
            'hrManagers' => $hrManagers,
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
        $hrManagerValidate = Request::validate([
            'first_name' => ['required', 'max:50'],
            'middle_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'gender' => ['required'],
            'email' => ['required', 'max:50', 'email'],
            'contact_number' => ['required', 'digits:11'],
        ]);

        $user = User::create([
            'email' => $hrManagerValidate['email'],
            'password' => Hash::make('12345678'),
            'user_type' => 2,
            'is_active' => 1
        ]);

        HrManager::create([
            'user_id' => $user['id'],
            'first_name' => $hrManagerValidate['first_name'],
            'middle_name' => $hrManagerValidate['middle_name'],
            'last_name' => $hrManagerValidate['last_name'],
            'gender' => $hrManagerValidate['gender'],
            'contact_number' => $hrManagerValidate['contact_number'],
        ]);
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
    public function update($id)
    {
        $hrManagerValidate = Request::validate([
            'first_name' => ['required', 'max:50'],
            'middle_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'gender' => ['required'],
            'email' => ['required', 'max:50', 'email'],
            'contact_number' => ['required', 'digits:11'],
        ]);

        $hrManager = HrManager::findOrFail($id);
        $user = HrManager::findOrFail($id)->user;

        $hrManager->first_name = $hrManagerValidate['first_name'];
        $hrManager->middle_name = $hrManagerValidate['middle_name'];
        $hrManager->last_name = $hrManagerValidate['last_name'];
        $hrManager->gender = $hrManagerValidate['gender'];
        $user->email = $hrManagerValidate['email'];
        $hrManager->contact_number = $hrManagerValidate['contact_number'];

        $user->save();
        $hrManager->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HrManager $hrManager)
    {
        //
    }
}
