<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to social provider
     */
    public function redirect($provider)
    {
        $this->validateProvider($provider);
        
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle social provider callback
     */
    public function callback($provider)
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Unable to login with ' . ucfirst($provider) . '. Please try again.');
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // User exists, log them in
            Auth::login($user);
            return redirect()->route('home')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        // Create new user
        $user = User::create([
            'name' => $socialUser->getName() ?? $socialUser->getNickname(),
            'username' => $this->generateUniqueUsername($socialUser),
            'email' => $socialUser->getEmail(),
            'password' => Hash::make(Str::random(32)), // Random password
            'role' => 'user',
            'email_verified_at' => now(), // Social accounts are considered verified
        ]);

        // Set avatar if available
        if ($socialUser->getAvatar()) {
            $user->avatar = $socialUser->getAvatar();
            $user->save();
        }

        // Set social links based on provider
        if ($provider === 'github' && $socialUser->getNickname()) {
            $user->github = 'https://github.com/' . $socialUser->getNickname();
        } elseif ($provider === 'twitter' && $socialUser->getNickname()) {
            $user->twitter = 'https://twitter.com/' . $socialUser->getNickname();
        }
        $user->save();

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Account created successfully! Welcome, ' . $user->name . '!');
    }

    /**
     * Validate the provider
     */
    protected function validateProvider($provider)
    {
        $allowedProviders = ['google', 'github', 'twitter'];
        
        if (!in_array($provider, $allowedProviders)) {
            abort(404);
        }
    }

    /**
     * Generate unique username from social user data
     */
    protected function generateUniqueUsername($socialUser)
    {
        $username = $socialUser->getNickname() ?? Str::slug($socialUser->getName() ?? 'user');
        $username = Str::lower($username);
        
        // Ensure uniqueness
        $originalUsername = $username;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
}

