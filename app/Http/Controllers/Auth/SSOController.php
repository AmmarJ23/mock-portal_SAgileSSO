<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class SSOController extends Controller
{
    /**
     * Initiate SSO login to SAgilePMT_UTM system.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showLoginForm()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login first.');
        }

        $user = Auth::user();
        
        // Generate a timestamp for request validation
        $timestamp = time();
        
        // Generate a secure token using timestamp and secret key
        $secretKey = Config::get('services.sagilepmt.key', 'default-secret-key');
        $token = hash('sha256', $timestamp . $secretKey . 'mock-portal-client');

        // Get the SAgilePMT_UTM URL from config
        $sagilePmtUrl = Config::get('services.sagilepmt.url', 'http://localhost:8000');
        $ssoUrl = "{$sagilePmtUrl}/login/sso/direct";
        
        // Store state in session for callback verification
        $state = Str::random(40);
        Session::put('sso_state', $state);
        
        // Include user data in the request
        $queryParams = http_build_query([
            'token' => $token,
            'timestamp' => $timestamp,
            'client_id' => 'mock-portal-client',
            'state' => $state,
            'redirect_url' => route('sso.callback'),
            'user_data' => base64_encode(json_encode([
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'matric_number' => $user->matric_number
            ]))
        ]);

        return redirect("{$ssoUrl}?{$queryParams}");
    }

    /**
     * Handle the callback from SAgilePMT_UTM system. Can be removed - not yet implemented
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleCallback(Request $request)
    {
        // Check for error response
        if ($request->has('error')) {
            return redirect()->route('login')
                ->with('error', $request->error);
        }

        // Verify the state token to prevent CSRF
        if ($request->state !== Session::get('sso_state')) {
            return redirect()->route('login')
                ->with('error', 'Invalid state token.');
        }

        // Clear the state from session
        Session::forget('sso_state');

        if (!$request->has('token')) {
            return redirect()->route('login')
                ->with('error', 'No token received from SAgilePMT.');
        }

        // Verify the token with SAgilePMT_UTM
        try {
            $sagilePmtUrl = Config::get('services.sagilepmt.url', 'http://localhost:8000');
            $response = Http::post("{$sagilePmtUrl}/api/verify-sso-token", [
                'token' => $request->token,
                'client_id' => 'mock-portal-client',
                'api_key' => Config::get('services.sagilepmt.key')
            ]);

            if (!$response->successful()) {
                throw new \Exception('Invalid token response');
            }

            $userData = $response->json();

            // Store the token and user data in session
            Session::put('sso_token', $request->token);
            Session::put('user_data', $userData);

            // Redirect to SAgilePMT dashboard
            return redirect($sagilePmtUrl . '/home');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Failed to verify SSO token. Please try again.');
        }
    }

    /**
     * Display the dashboard with SSO data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        $userData = Session::get('user_data');
        
        if (!$userData) {
            return redirect()->route('login')
                ->with('error', 'No SSO session found.');
        }

        return view('dashboard', ['userData' => $userData]);
    }
} 