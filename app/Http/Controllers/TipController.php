<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TipController extends Controller
{
    /**
     * Show the tip submission form
     */
    public function create()
    {
        // Generate CAPTCHA answer (42 for "forty-two")
        Session::put('tip_captcha_answer', 42);
        $captchaQuestion = 'Write "forty-two" as a number';

        return view('tips.create', compact('captchaQuestion'));
    }

    /**
     * Store a newly submitted tip
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string|min:10|max:10000',
            'captcha_answer' => 'required|integer',
        ]);

        // Validate CAPTCHA (answer should be 42 for "forty-two")
        $correctAnswer = Session::get('tip_captcha_answer');
        if (!$correctAnswer || (int)$request->captcha_answer !== (int)$correctAnswer) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => ['captcha_answer' => ['CAPTCHA answer is incorrect. Please try again.']]
                ], 422);
            }
            return back()->withErrors(['captcha_answer' => 'CAPTCHA answer is incorrect. Please try again.'])->withInput();
        }

        // Clear CAPTCHA from session after successful validation
        Session::forget('tip_captcha_answer');

        // Get user info if authenticated
        $userId = Auth::id();
        $email = null;
        $name = null;

        if ($userId) {
            $user = Auth::user();
            $email = $user->email;
            $name = $user->name;
        }

        $tip = Tip::create([
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'status' => 'pending',
            'user_id' => $userId,
            'email' => $email,
            'name' => $name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your tip! We will review it and get back to you if needed.'
            ]);
        }

        return redirect()->route('tips.create')->with('success', 'Thank you for your tip! We will review it and get back to you if needed.');
    }
}

