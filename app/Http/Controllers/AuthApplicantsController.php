<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthApplicantsController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'middleName' => 'nullable|string',
            'lastName' => 'required|string',
            'email' => 'required|email|unique:users',
            'gender' => 'required|string',
            'contact_number' => 'required|numeric|digits:11',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $adminId = User::where('user_type', 3)->first();

        $user = User::create([
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'user_type' => 0,
            'is_active' => 1
        ]);

        Applicant::create([
            'user_id' => $user->id,
            'first_name' => $request->input('firstName'),
            'middle_name' => $request->input('middleName') ?? '',
            'last_name' => $request->input('lastName'),
            'gender' => $request->input('gender'),
            'contact_number' => $request->input('contact_number'),
        ]);

        Notification::create([
            'title' => 'A new account for ' . $request->input('firstName') . ' ' . $request->input('lastName') . ' has been successfully created in our system.',
            'user_id' => array_merge([$adminId->id]),
            'author_id' => $user->id,
            'status' => 0
        ]);

        Notification::create([
            'title' => "Welcome to FAMSI Job Portal! Browse jobs and start applying today. Good luck!",
            'user_id' => array_merge([$user->id]),
            'author_id' => $adminId->id,
            'status' => 0
        ]);

        $token = $user->createToken('Applicant Dashboard')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }

    // Log in a user
    public function login(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        if ($user->user_type === 0) {
            $token = $user->createToken('Applicant Dashboard')->plainTextToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'These credentials do not match our records.'], 401);
        }
    } else {
        return response()->json(['error' => 'These credentials do not match our records.'], 401);
    }
}

    // Log out a user
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Successfully logged out']);
    }

    // Refresh access token
    public function refresh(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        $newToken = $request->user()->createToken('MyApp')->accessToken;

        return response()->json(['token' => $newToken], 200);
    }

    // Get the authenticated user
    public function details(Request $request)
    {
        return response()->json($request->user());
    }
}
