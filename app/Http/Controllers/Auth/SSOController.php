<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        // Generate a state token to prevent CSRF
        $state = Str::random(40);
        session(['sso_state' => $state]);

        // Get the return URL for after successful authentication
        $returnUrl = route('sso.callback');

        // Redirect to the external system's SSO endpoint
        $externalSystemUrl = config('services.student_portal.url');
        $ssoUrl = "{$externalSystemUrl}/auth/sso/initiate";
        
        $queryParams = http_build_query([
            'state' => $state,
            'return_url' => $returnUrl,
            'client_id' => config('services.student_portal.key')
        ]);

        return redirect("{$ssoUrl}?{$queryParams}");
    }

    /**
     * Handle the callback from the external system.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleCallback(Request $request)
    {
        // Verify the state token to prevent CSRF
        if ($request->state !== session('sso_state')) {
            return redirect()->route('login')
                ->with('error', 'Invalid state token.');
        }

        // Clear the state from session
        session()->forget('sso_state');

        // Store the token and user data received from external system
        session([
            'sso_token' => $request->token,
            'user_data' => $request->user_data
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Display the dashboard with SSO data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        $userData = session('user_data');
        
        if (!$userData) {
            return redirect()->route('login')
                ->with('error', 'No SSO session found.');
        }

        return view('dashboard', ['userData' => $userData]);
    }
} 