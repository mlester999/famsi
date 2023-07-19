<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Applicant;
use App\Models\HrManager;
use App\Models\HrStaff;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
            $user = $event->user;
            $request = request();

            if($user && $user->user_type === User::ADMIN) {
                $adminInfo = Admin::where('user_id', $user->id)->first();

                activity()
                ->causedBy($user)
                ->event('login')
                ->withProperties(['ipAddress' => request()->ip(), 'user' => $adminInfo->first_name . ' ' . $adminInfo->last_name, 'role' => 'Admin'])
                ->log($adminInfo->first_name . ' logged in to the system.');
            } else if($user && $user->user_type === User::HR_MANAGER) {
                $hrManagerInfo = HrManager::where('user_id', $user->id)->first();

                activity()
                ->causedBy($user)
                ->event('login')
                ->withProperties(['ipAddress' => request()->ip(), 'user' => $hrManagerInfo->first_name . ' ' . $hrManagerInfo->last_name, 'role' => 'HR Manager'])
                ->log('HR Manager: ' . $hrManagerInfo->first_name . $hrManagerInfo->last_name . ', logged in to the system.');
            } else if($user && $user->user_type === User::HR_STAFF) {
                $hrStaffInfo = HrStaff::where('user_id', $user->id)->first();

                activity()
                ->causedBy($user)
                ->event('login')
                ->withProperties(['ipAddress' => request()->ip(), 'user' => $hrStaffInfo->first_name . ' ' . $hrStaffInfo->last_name, 'role' => 'HR Staff'])
                ->log('HR Staff: ' . $hrStaffInfo->first_name . $hrStaffInfo->last_name . ', logged in to the system.');
            } else if($user && $user->user_type === User::APPLICANT) {
                $applicantInfo = Applicant::where('user_id', $user->id)->first();

                activity()
                ->causedBy($user)
                ->event('login')
                ->withProperties(['ipAddress' => request()->ip(), 'user' => $applicantInfo->first_name . ' ' . $applicantInfo->last_name, 'role' => 'Applicant'])
                ->log('Applicant: ' . $applicantInfo->first_name . $applicantInfo->last_name . ', logged in to the system.');
            }
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
