<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Psr\Clock\ClockInterface;

class ClerkAuthService
{
    protected $config;

    public function __construct()
    {
        $publicKey = config('services.clerk.jwks_public_key');
        
        if (!$publicKey) {
            throw new \Exception("Clerk JWKS public key is not configured.");
        }

        // Initialize JWT configuration for asymmetric RSA signature verification
        $this->config = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::plainText('dummy_key'), // We don't need a private key for verification
            InMemory::plainText($publicKey)
        );
    }

    /**
     * Verify Clerk JWT token and get claims
     */
    public function verifyToken(string $tokenString): array
    {
        try {
            // Parse the token string
            $token = $this->config->parser()->parse($tokenString);

            // Create an anonymous clock since lcobucci/clock is not installed
            $clock = new class implements ClockInterface {
                public function now(): \DateTimeImmutable {
                    return new \DateTimeImmutable();
                }
            };

            // Define validation constraints
            $constraints = [
                new SignedWith($this->config->signer(), $this->config->verificationKey()),
                new LooseValidAt($clock, new \DateInterval('PT30S')) // Allow 30s clock skew
            ];

            // Validate token signature and expiration
            if (!$this->config->validator()->validate($token, ...$constraints)) {
                throw new \Exception("Invalid token signature or expired token.");
            }

            return $token->claims()->all();
            
        } catch (\Exception $e) {
            Log::error('Clerk Token Verification Error: ' . $e->getMessage());
            throw new \Exception("Invalid Clerk token: " . $e->getMessage());
        }
    }

    /**
     * Create or update user from Clerk auth data
     */
    public function createOrUpdateUser(array $claims, ?string $frontendEmail = null): User
    {
        // Extract user data from Clerk claims
        $clerkId = $claims['sub'] ?? null;
        
        // Clerk tokens might contain email in claims. If not, use the one passed from frontend.
        $email = $claims['email'] ?? $frontendEmail ?? $clerkId . '@clerk.local'; 
        
        $name = $claims['name'] ?? 'User';

        if (!$clerkId) {
            throw new \Exception("Clerk ID (sub) is missing from token.");
        }

        // Check if user exists by email
        $user = User::where('email', $email)->first();

        if ($user) {
            // Update existing user if needed
            return $user;
        }

        // Create new user
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make(Str::random(32)); // Random password, they won't use it
        $user->email_verified_at = now(); // Clerk handles verification
        
        // Set default role for new users
        $user->role = 'user'; 
        
        $user->save();

        return $user;
    }
}
