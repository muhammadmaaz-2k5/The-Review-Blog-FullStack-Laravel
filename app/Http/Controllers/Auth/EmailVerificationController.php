<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    /**
     * Show the email verification notice
     */
    public function notice()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        return view('auth.verify-email');
    }

    /**
     * Mark the user's email address as verified
     */
    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home')->with('info', 'Email already verified.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('home')->with('success', 'Email verified successfully!');
    }

    /**
     * Resend the email verification notification
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home')->with('info', 'Email already verified.');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent! Please check your email.');
    }
}

