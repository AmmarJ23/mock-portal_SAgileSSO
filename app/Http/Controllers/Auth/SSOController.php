<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\LecturerStudentAssignment;
use App\Models\User;

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
                'identifier' => $user->matric_number
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

    /**
     * Sync lecturer-student assignments with SAgilePMT.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncWithSAgile(Request $request)
    {
        try {
            // Generate timestamp and token similar to showLoginForm
            $timestamp = time();
            $secretKey = Config::get('services.sagilepmt.key', 'default-secret-key');
            $token = hash('sha256', $timestamp . $secretKey . 'mock-portal-client');

            // Get all lecturer-student assignments grouped by lecturer
            $assignments = LecturerStudentAssignment::all()
                ->groupBy('lecturer_staff_id');

            // Format assignments data
            $formattedAssignments = [];

            foreach ($assignments as $lecturerStaffId => $lecturerAssignments) {
                // Get lecturer data
                $lecturer = User::where('staff_id', $lecturerStaffId)->first();
                
                if (!$lecturer) {
                    continue; // Skip if lecturer not found
                }

                $assignmentGroup = [
                    'lecturer' => [
                        'staff_id' => $lecturer->staff_id,
                        'name' => $lecturer->name,
                        'username' => $lecturer->username,
                        'email' => $lecturer->email,
                    ],
                    'students' => []
                ];

                // Add students with their details
                foreach ($lecturerAssignments as $assignment) {
                    // Get student data
                    $student = User::where('matric_number', $assignment->student_matric_number)->first();
                    
                    if (!$student) {
                        continue; // Skip if student not found
                    }

                    $assignmentGroup['students'][] = [
                        'matric_number' => $student->matric_number,
                        'name' => $student->name,
                        'username' => $student->username,
                        'email' => $student->email,
                    ];
                }

                $formattedAssignments[] = $assignmentGroup;
            }

            // Prepare the payload
            $payload = [
                'token' => $token,
                'timestamp' => $timestamp,
                'client_id' => 'mock-portal-client',
                'state' => 'test_state',
                'redirect_url' => 'http://127.0.0.1:8001/',
                'assignments' => $formattedAssignments
            ];

            // Get the SAgilePMT_UTM URL from config
            $sagilePmtUrl = Config::get('services.sagilepmt.url', 'http://localhost:8000');
            $syncUrl = "{$sagilePmtUrl}/api/lecturer-student-assignment";

            // Send the request to SAgilePMT
            $response = Http::post($syncUrl, $payload);

            if (!$response->successful()) {
                throw new \Exception('Failed to sync with SAgilePMT: ' . $response->body());
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully synced with SAgilePMT'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync with SAgilePMT: ' . $e->getMessage()
            ], 500);
        }
    }
} 