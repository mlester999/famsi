<?php

namespace App\Actions\Fortify;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'gender' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'numeric', 'digits:11'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'user_type' => 0,
            'is_active' => 0
        ]);

        Applicant::create([
            'user_id' => $user->id,
            'first_name' => $input['firstName'],
            'middle_name' => $input['middleName'] ?? '',
            'last_name' => $input['lastName'],
            'gender' => $input['gender'],
            'contact_number' => $input['contact_number'],
        ]);

        return $user;
    }
}
