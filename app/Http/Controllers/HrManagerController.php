<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Applicant;
use App\Models\HrManager;
use App\Models\HrStaff;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
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
                $query->whereHas('user', function ($query) use ($search) {
                    $query->whereRaw('LOWER(email) LIKE LOWER(?)', ['%' . $search . '%'])
                        ->orWhereRaw('LOWER(contact_number) LIKE LOWER(?)', ['%' . $search . '%']);
                })
                        ->orWhereRaw('LOWER(first_name) LIKE LOWER(?)', ['%' . $search . '%'])
                        ->orWhereRaw('LOWER(middle_name) LIKE LOWER(?)', ['%' . $search . '%'])
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
            'email_verified_at' => $hrManager->user->email_verified_at,
            'contact_number' => $hrManager->contact_number,
            'is_active' => $hrManager->user->is_active,
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
            'middle_name' => ['max:50'],
            'last_name' => ['required', 'max:50'],
            'gender' => ['required'],
            'email' => ['required', 'max:50', 'email', 'unique:users'],
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

        activity()
        ->performedOn(HrManager::where('user_id', $user['id'])->first())
        ->causedBy(auth()->user())
        ->event('created')
        ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
        ->log("Created a HR Manager account with the name of {$hrManagerValidate['first_name']} {$hrManagerValidate['last_name']}");
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
            'middle_name' => ['max:50'],
            'last_name' => ['required', 'max:50'],
            'gender' => ['required'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore(HrManager::findOrFail($id)->user->id)],
            'contact_number' => ['required', 'digits:11'],
        ]);

        $hrManager = HrManager::findOrFail($id);
        $user = HrManager::findOrFail($id)->user;

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

        if($hrManagerValidate['first_name'] !== $hrManager->first_name) {
            activity()
            ->performedOn(HrManager::where('user_id', $user['id'])->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a HR Manager account's first name from {$hrManager->first_name} to {$hrManagerValidate['first_name']}");

            $hrManager->first_name = $hrManagerValidate['first_name'];
        }

        if($hrManagerValidate['middle_name'] !== $hrManager->middle_name) {
            activity()
            ->performedOn(HrManager::where('user_id', $user['id'])->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a HR Manager account's middle name from {$hrManager->middle_name} to {$hrManagerValidate['middle_name']}");

            $hrManager->middle_name = $hrManagerValidate['middle_name'];
        }

        if($hrManagerValidate['last_name'] !== $hrManager->last_name) {
            activity()
            ->performedOn(HrManager::where('user_id', $user['id'])->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a HR Manager account's last name from {$hrManager->last_name} to {$hrManagerValidate['last_name']}");

            $hrManager->last_name = $hrManagerValidate['last_name'];
        }

        if($hrManagerValidate['gender'] !== $hrManager->gender) {
            activity()
            ->performedOn(HrManager::where('user_id', $user['id'])->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a HR Manager account's gender from {$hrManager->gender} to {$hrManagerValidate['gender']}");

            $hrManager->gender = $hrManagerValidate['gender'];
        }

        if($hrManagerValidate['email'] !== $user->email) {
            activity()
            ->performedOn(HrManager::where('user_id', $user['id'])->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a HR Manager account's email from {$user->email} to {$hrManagerValidate['email']}");

            $user->email = $hrManagerValidate['email'];
        }

        if($hrManagerValidate['contact_number'] !== $hrManager->contact_number) {
            activity()
            ->performedOn(HrManager::where('user_id', $user['id'])->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a HR Manager account's contact number from {$hrManager->contact_number} to {$hrManagerValidate['contact_number']}");

            $hrManager->contact_number = $hrManagerValidate['contact_number'];
        }

        $user->save();
        $hrManager->save();
    }

    /**
     * Activate the specified resource.
     */
    public function activate($id)
    {
        $user = HrManager::findOrFail($id)->user;

        $user->is_active = 1;

        $user->save();
    }

    /**
     * Deactivate the specified resource.
     */
    public function deactivate($id)
    {
        $user = HrManager::findOrFail($id)->user;

        $user->is_active = 0;

        $user->save();
    }
}
