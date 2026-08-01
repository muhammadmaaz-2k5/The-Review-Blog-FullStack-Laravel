<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display a listing of contact messages
     */
    public function index(Request $request)
    {
        $query = ContactMessage::with(['user', 'repliedBy']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::where('status', 'unread')->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
        ];

        return view('admin.contacts.index', compact('messages', 'stats'));
    }

    /**
     * Display the specified contact message
     */
    public function show(ContactMessage $contact)
    {
        // Mark as read if unread
        if ($contact->status === 'unread') {
            $contact->markAsRead();
        }

        $contact->load(['user', 'repliedBy']);

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Mark message as read
     */
    public function markAsRead(ContactMessage $contact)
    {
        $contact->markAsRead();

        return redirect()->back()->with('success', 'Message marked as read.');
    }

    /**
     * Reply to contact message
     */
    public function reply(Request $request, ContactMessage $contact)
    {
        $request->validate([
            'reply_message' => 'required|string|min:10|max:5000',
        ]);

        $contact->markAsReplied(Auth::id(), $request->reply_message);

        // Send reply email
        try {
            Mail::send([], [], function ($message) use ($contact, $request) {
                $message->to($contact->email)
                    ->subject('Re: ' . ($contact->subject ?: 'Your Contact Message'))
                    ->html("
                        <p>Hello {$contact->name},</p>
                        <p>" . nl2br(e($request->reply_message)) . "</p>
                        <p>Best regards,<br>" . config('app.name') . " Team</p>
                    ");
            });
        } catch (\Exception $e) {
            Log::error('Failed to send reply email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Reply saved but email could not be sent.');
        }

        return redirect()->back()->with('success', 'Reply sent successfully.');
    }

    /**
     * Delete contact message
     */
    public function destroy(ContactMessage $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Message deleted successfully.');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,mark_read,mark_unread',
            'messages' => 'required|array',
            'messages.*' => 'exists:contact_messages,id',
        ]);

        $messages = ContactMessage::whereIn('id', $request->messages);

        switch ($request->action) {
            case 'delete':
                $messages->delete();
                $message = 'Selected messages deleted.';
                break;
            case 'mark_read':
                $messages->update(['status' => 'read', 'read_at' => now()]);
                $message = 'Selected messages marked as read.';
                break;
            case 'mark_unread':
                $messages->update(['status' => 'unread', 'read_at' => null]);
                $message = 'Selected messages marked as unread.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}

