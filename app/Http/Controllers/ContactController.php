<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Store a newly created contact message
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $contactMessage = ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'unread',
            'user_id' => Auth::id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send email notification to admin
        $contactEmail = config('mail.contact_email', 'muhamamdmaaz65@gmail.com');
        try {
            Mail::send([], [], function ($message) use ($contactMessage, $contactEmail) {
                $message->to($contactEmail)
                    ->subject('New Contact Message: ' . ($contactMessage->subject ?: 'No Subject'))
                    ->html("
                        <h2>New Contact Message</h2>
                        <p><strong>Name:</strong> {$contactMessage->name}</p>
                        <p><strong>Email:</strong> {$contactMessage->email}</p>
                        <p><strong>Subject:</strong> " . ($contactMessage->subject ?: 'No Subject') . "</p>
                        <p><strong>Message:</strong></p>
                        <p>" . nl2br(e($contactMessage->message)) . "</p>
                        <p><a href='" . route('admin.contacts.show', $contactMessage->id) . "'>View in Admin Panel</a></p>
                    ");
            });
        } catch (\Exception $e) {
            // Log error but don't fail the request
            Log::error('Failed to send contact email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
