<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\Drama;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SendRandomDramaNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-random-drama-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a push notification with a random drama recommendation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting random drama notification process...');

        // 1. Check Credentials
        $serviceAccountPath = storage_path('app/private/app/firebase_credentials.json');
        if (!file_exists($serviceAccountPath)) {
            $this->error('Firebase Service Account file not found at ' . $serviceAccountPath);
            Log::error('SendRandomDramaNotification: Firebase credentials missing.');
            return 1;
        }

        // 2. Get Random Drama
        $drama = Drama::inRandomOrder()->first();
        if (!$drama) {
            $this->error('No dramas found in database.');
            Log::warning('SendRandomDramaNotification: No dramas found.');
            return 1;
        }

        $this->info("Selected Drama: {$drama->title}");

        // 3. Prepare Notification Content
        $title = "Watch Now: " . $drama->title;
        $body = $drama->description 
            ? Str::limit(strip_tags($drama->description), 100) 
            : "Check out the latest episode of {$drama->title} now on Nazaara Box.";
        
        // Ensure Image URL is absolute and supports external links (e.g. TMDB)
        $imageUrl = null;
        if ($drama->image) {
             $imageUrl = $drama->image_url;
             $this->info("Image URL: " . $imageUrl);
        }

        // 4. Send Notification
        try {
            $factory = (new Factory)->withServiceAccount($serviceAccountPath);
            $messaging = $factory->createMessaging();

            // Create Notification Object
            $notification = Notification::create($title, $body);
            if ($imageUrl) {
                $notification = $notification->withImageUrl($imageUrl);
            }

            // We send to 'all' topic
            $message = CloudMessage::withTarget('topic', 'all')
                ->withNotification($notification)
                ->withData([
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'drama',
                    'drama_id' => (string)$drama->id,
                    'slug' => (string)$drama->slug,
                    'image' => (string)$imageUrl,
                    'image_url' => (string)$imageUrl, // Compatibility
                    'thumbnail' => (string)$imageUrl, // Compatibility
                    'title' => (string)$title,
                    'body' => (string)$body
                ]);

            $messaging->send($message);
            
            $this->info('Notification sent successfully!');
            Log::info("SendRandomDramaNotification: Sent for drama {$drama->id} ({$drama->title})");
            return 0;

        } catch (\Throwable $e) {
            $this->error('Failed to send notification: ' . $e->getMessage());
            Log::error('SendRandomDramaNotification Error: ' . $e->getMessage());
            return 1;
        }
    }
}
