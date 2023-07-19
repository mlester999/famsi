<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\Admin;
use App\Models\Applicant;
use App\Models\HrManager;
use App\Models\HrStaff;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::authenticateUsing(function (Request $request){
            $user = User::where(['email' => $request->email])->first();

            // Admin
            if ($user && $user->is_active && Hash::check($request->password, $user->password)) {

                return $user;
            }

            // if ($hrManager && $hrManager->is_active && Hash::check($request->password, $hrManager->password)) {
            //     $hrManagerInfo = HrManager::where('user_id', $hrManager->id)->first();

            //     activity()
            //     ->causedBy($hrManager)
            //     ->event('login')
            //     ->withProperties(['ipAddress' => $request->ip()])
            //     ->log('HR Manager: ' . $hrManagerInfo->first_name . $hrManagerInfo->last_name . ', logged in to the website.');

            //     return $hrManager;
            // }

            // if ($hrStaff && $hrStaff->is_active && Hash::check($request->password, $hrStaff->password)) {
            //     $hrStaffInfo = HrStaff::where('user_id', $hrStaff->id)->first();

            //     activity()
            //     ->causedBy($hrStaff)
            //     ->event('login')
            //     ->withProperties(['ipAddress' => $request->ip()])
            //     ->log('HR Staff: ' . $hrStaffInfo->first_name . $hrStaffInfo->last_name . ', logged in to the website.');

            //     return $hrStaff;
            // }

            // if ($applicant && $applicant->is_active && Hash::check($request->password, $applicant->password)) {
            //     $applicantInfo = Applicant::where('user_id', $applicant->id)->first();

            //     activity()
            //     ->causedBy($applicant)
            //     ->event('login')
            //     ->withProperties(['ipAddress' => $request->ip()])
            //     ->log('Applicant: ' . $applicantInfo->first_name . $applicantInfo->last_name . ', logged in to the website.');

            //     return $applicant;
            // }
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
