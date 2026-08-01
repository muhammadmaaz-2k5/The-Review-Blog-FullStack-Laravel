<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\FirebaseAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FirebaseAuthController extends Controller
{
    protected $firebaseAuth;

    public function __construct(FirebaseAuthService $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    /**
     * Handle Firebase authentication callback
     */
    public function authenticate(Request $request)
    {
        // Increase execution time for Firebase token verification
        // This is needed because SSL certificate issues can cause delays
        set_time_limit(60); // 60 seconds instead of default 30
        
        $request->validate([
            'id_token' => 'required|string',
        ]);

        try {
            // Verify the Firebase ID token
            $firebaseUser = $this->firebaseAuth->verifyIdToken($request->id_token);

            // Create or update user in database
            $user = $this->firebaseAuth->createOrUpdateUser($firebaseUser);

            // Log the user in
            Auth::login($user, $request->boolean('remember', false));

            // Redirect based on user role
            if ($user->isAdmin()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('admin.dashboard'),
                    'message' => 'Welcome back, ' . $user->name . '!',
                ]);
            }

            return response()->json([
                'success' => true,
                'redirect' => route('home'),
                'message' => 'Welcome, ' . $user->name . '!',
            ]);

        } catch (\Exception $e) {
            Log::error('Firebase authentication error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Authentication failed: ' . $e->getMessage(),
            ], 400);
        }
    }
}

