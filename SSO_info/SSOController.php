<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class SSOController extends Controller
{
    /**
     * Show the SSO login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.sso-login');
    }

    /**
     * Handle direct SSO login from student portal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleDirectLogin(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'timestamp' => 'required|numeric',
        ]);

        try {
            // Verify if the request is not too old (prevent replay attacks)
            $requestTime = (int)$request->timestamp;
            $currentTime = time();
            
            if ($currentTime - $requestTime > 300) { // 5 minutes expiry
                throw ValidationException::withMessages([
                    'token' => ['SSO login link has expired.'],
                ]);
            }

            // Verify token with student portal
            $response = Http::post(config('services.student_portal.url') . '/api/verify-sso-token', [
                'token' => $request->token,
                'api_key' => config('services.student_portal.key'),
            ]);

            if (!$response->successful()) {
                throw ValidationException::withMessages([
                    'token' => ['Invalid SSO token.'],
                ]);
            }

            $studentData = $response->json();

            // Check if user exists
            $user = User::where('matric_number', $studentData['matric_number'])->first();

            if (!$user) {
                // Create new user for first-time login
                $user = User::create([
                    'name' => $studentData['name'],
                    'email' => $studentData['email'],
                    'username' => $studentData['matric_number'],
                    'matric_number' => $studentData['matric_number'],
                    'password' => Hash::make(Str::random(16)), // Random password as SSO users don't need it
                ]);

                // You can add any additional first-time setup here
                // For example, assigning default roles or creating initial settings
            }

            // Login the user
            Auth::login($user);

            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Unable to authenticate with the student portal. Please try again later.');
        }
    }

    /**
     * Handle manual SSO login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'matric_number' => 'required|string',
        ]);

        try {
            // Make API call to student portal
            $response = Http::post(config('services.student_portal.url') . '/api/verify-student', [
                'matric_number' => $request->matric_number,
                'api_key' => config('services.student_portal.key'),
            ]);

            if (!$response->successful()) {
                throw ValidationException::withMessages([
                    'matric_number' => ['Invalid credentials or student portal is unavailable.'],
                ]);
            }

            $studentData = $response->json();

            // Check if user exists
            $user = User::where('matric_number', $studentData['matric_number'])->first();

            if (!$user) {
                // Create new user for first-time login
                $user = User::create([
                    'name' => $studentData['name'],
                    'email' => $studentData['email'],
                    'username' => $studentData['matric_number'],
                    'matric_number' => $studentData['matric_number'],
                    'password' => Hash::make(Str::random(16)), // Random password as SSO users don't need it
                ]);

                // You can add any additional first-time setup here
                // For example, assigning default roles or creating initial settings
            }

            // Login the user
            Auth::login($user);

            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            return back()->with('error', 'Unable to authenticate with the student portal. Please try again later.');
        }
    }
} 