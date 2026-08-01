<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use GuzzleHttp\Client;

class FirebaseAuthService
{
    protected $auth;

    public function __construct()
    {
        $credentialsPath = config('services.firebase.credentials');
        
        if (!file_exists($credentialsPath)) {
            throw new \Exception("Firebase credentials file not found at: {$credentialsPath}");
        }

        // Create HTTP client with SSL verification disabled for local development
        // WARNING: Only use this in local development, never in production!
        if (config('app.env') === 'local' || config('app.debug')) {
            // Set cURL option to disable SSL verification for local development
            // This is the primary fix for Windows SSL certificate issues
            ini_set('curl.cainfo', '');
            
            // Set environment variable that Guzzle might respect
            putenv('GUZZLE_CURL_OPTIONS=' . json_encode(['CURLOPT_SSL_VERIFYPEER' => false, 'CURLOPT_SSL_VERIFYHOST' => false]));
        }
        
        // Create Firebase Factory with service account
        $factory = (new Factory)->withServiceAccount($credentialsPath);
        
        // Try to configure Factory with a custom HTTP client
        // This helps with Firebase SDK's HTTP requests, but may not cover all internal requests
        if (config('app.env') === 'local' || config('app.debug')) {
            try {
                $httpClient = new Client([
                    'verify' => false,
                    'timeout' => 15,
                    'connect_timeout' => 10,
                ]);
                
                $reflection = new \ReflectionClass($factory);
                if ($reflection->hasMethod('withHttpClient')) {
                    $factory = $reflection->getMethod('withHttpClient')->invoke($factory, $httpClient);
                }
            } catch (\Exception $e) {
                Log::debug('Firebase withHttpClient configuration: ' . $e->getMessage());
            }
        }
        
        $this->auth = $factory->createAuth();
    }

    /**
     * Verify Firebase ID token and get user data
     */
    public function verifyIdToken(string $idToken): array
    {
        // Increase execution time for token verification
        // SSL certificate issues can cause delays
        set_time_limit(60);
        
        try {
            $verifiedToken = $this->auth->verifyIdToken($idToken);
            return $verifiedToken->claims()->all();
        } catch (\Exception $e) {
            // Check if it's a timeout/SSL error
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'SSL certificate') || str_contains($errorMessage, 'timeout')) {
                throw new \Exception("SSL certificate error. Please configure php.ini: curl.cainfo = \"\" for local development. Error: " . $errorMessage);
            }
            throw new \Exception("Invalid Firebase token: " . $errorMessage);
        }
    }

    /**
     * Create or update user from Firebase auth data
     */
    public function createOrUpdateUser(array $firebaseClaims): User
    {
        // Extract user data from Firebase token claims
        $email = $firebaseClaims['email'] ?? null;
        $uid = $firebaseClaims['sub'] ?? $firebaseClaims['uid'] ?? null;
        $name = $firebaseClaims['name'] ?? $firebaseClaims['display_name'] ?? 'User';
        $photoUrl = $firebaseClaims['picture'] ?? $firebaseClaims['photo_url'] ?? null;

        if (!$email) {
            throw new \Exception("Email is required for Firebase authentication");
        }

        // Check if user exists by email
        $user = User::where('email', $email)->first();

        if ($user) {
            // Update existing user
            $user->name = $name;
            if ($photoUrl && !$user->avatar) {
                $user->avatar = $photoUrl;
            }
            // Mark email as verified if not already
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
            }
            $user->save();
        } else {
            // Create new user
            $username = $this->generateUniqueUsername($name, $email);
            
            $user = User::create([
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'password' => Hash::make(Str::random(32)), // Random password since Firebase handles auth
                'role' => 'user',
                'avatar' => $photoUrl,
                'email_verified_at' => now(), // Firebase users are considered verified
            ]);
        }

        return $user;
    }

    /**
     * Generate unique username from name or email
     */
    protected function generateUniqueUsername(string $name, string $email): string
    {
        // Try to create username from name
        $username = Str::slug(Str::before($name, ' '));
        
        // If name is empty or too short, use email prefix
        if (empty($username) || strlen($username) < 3) {
            $username = Str::slug(Str::before($email, '@'));
        }
        
        $username = Str::lower($username);
        $originalUsername = $username;
        $counter = 1;

        // Ensure uniqueness
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        return $username;
    }
}

