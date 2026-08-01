<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ClerkAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClerkAuthController extends Controller
{
    protected $clerkAuth;

    public function __construct(ClerkAuthService $clerkAuth)
    {
        $this->clerkAuth = $clerkAuth;
    }

    /**
     * Handle Clerk authentication callback
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
            'email' => 'nullable|string|email',
            'name' => 'nullable|string',
        ]);

        try {
            // Verify the Clerk ID token
            $claims = $this->clerkAuth->verifyToken($request->id_token);

            // Create or update user in database
            // We pass frontend email/name in case they are missing from token claims
            if (!isset($claims['email']) && $request->email) {
                $claims['email'] = $request->email;
            }
            if (!isset($claims['name']) && $request->name) {
                $claims['name'] = $request->name;
            }
            
            $user = $this->clerkAuth->createOrUpdateUser($claims, $request->email);

            // Log the user in via Laravel session
            Auth::login($user, true);

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

        } catch (\Throwable $e) {
            Log::error('Clerk authentication error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());

            return response()->json([
                'success' => false,
                'message' => 'Authentication failed: ' . $e->getMessage(),
            ], 400);
        }
    }
}
