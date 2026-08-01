<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminNotificationController extends Controller
{
    /**
     * Show the notification creation form.
     */
    public function create(Request $request)
    {
        $hasCredentials = file_exists(storage_path('app/private/app/firebase_credentials.json'));
        
        return view('admin.notifications.create', compact('hasCredentials'));
    }

    /**
     * Upload the Firebase Service Account credentials.
     */
    public function uploadCredentials(Request $request)
    {
        $request->validate([
            'firebase_credentials' => 'required|file|mimetypes:application/json,text/plain',
        ]);

        try {
            // Move the file to storage/app/private/app/firebase_credentials.json
            $request->file('firebase_credentials')->move(storage_path('app/private/app'), 'firebase_credentials.json');
            
            return back()->with('success', 'Firebase credentials uploaded successfully! You can now send notifications.');
        } catch (\Throwable $e) {
            Log::error('Firebase Credentials Upload Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to upload credentials: ' . $e->getMessage());
        }
    }

    /**
     * Send the notification to the 'all' topic.
     */
    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image_url' => 'nullable|url',
        ]);

        try {
            // Path to your Firebase Service Account JSON file
            $serviceAccountPath = storage_path('app/private/app/firebase_credentials.json');

            if (!file_exists($serviceAccountPath)) {
                return back()->with('error', 'Firebase Service Account file not found at ' . $serviceAccountPath . '. Please upload it.');
            }

            $factory = (new Factory)->withServiceAccount($serviceAccountPath);
            $messaging = $factory->createMessaging();

            $title = $request->input('title');
            $body = $request->input('body');
            $imageUrl = $request->input('image_url');

            $notification = Notification::create($title, $body);
            
            if ($imageUrl) {
                $notification = $notification->withImageUrl($imageUrl);
            }

            $message = CloudMessage::withTarget('topic', 'all')
                ->withNotification($notification)
                ->withData([
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'general',
                    'title' => $title,
                    'body' => $body,
                    'image' => (string)$imageUrl
                ]);

            $messaging->send($message);

            return back()->with('success', 'Notification sent successfully to all users!');
        } catch (\Throwable $e) {
            Log::error('Firebase Notification Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to send notification: ' . $e->getMessage());
        }
    }
}
