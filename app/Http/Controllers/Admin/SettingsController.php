<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FacebookService;
use App\Services\TwitterService;
use App\Services\InstagramService;
use App\Services\ThreadsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    protected $facebookService;
    protected $twitterService;
    protected $instagramService;
    protected $threadsService;

    public function __construct(
        FacebookService $facebookService,
        TwitterService $twitterService,
        InstagramService $instagramService,
        ThreadsService $threadsService
    ) {
        $this->facebookService = $facebookService;
        $this->twitterService = $twitterService;
        $this->instagramService = $instagramService;
        $this->threadsService = $threadsService;
    }

    /**
     * Display settings page
     */
    public function index()
    {
        return view('admin.settings.index', [
            'facebookEnabled' => config('services.facebook.enabled', false),
            'facebookPageId' => config('services.facebook.page_id'),
            'facebookApiVersion' => config('services.facebook.api_version', 'v18.0'),
            'twitterEnabled' => config('services.twitter.enabled', false),
            'twitterApiVersion' => config('services.twitter.api_version', '2'),
            'instagramEnabled' => config('services.instagram.enabled', false),
            'instagramPageId' => config('services.instagram.page_id'),
            'instagramApiVersion' => config('services.instagram.api_version', 'v18.0'),
            'threadsEnabled' => config('services.threads.enabled', false),
            'threadsPageId' => config('services.threads.page_id'),
            'threadsApiVersion' => config('services.threads.api_version', 'v18.0'),
        ]);
    }

    /**
     * Update Facebook settings
     */
    public function updateFacebook(Request $request)
    {
        $validated = $request->validate([
            'enabled' => 'nullable|boolean',
            'page_id' => 'nullable|string|max:255',
            'page_access_token' => 'nullable|string|max:500',
            'api_version' => 'nullable|string|max:10',
        ]);

        // Only update access token if a new one is provided
        if (empty($validated['page_access_token'])) {
            // Keep existing token if not provided
            $validated['page_access_token'] = config('services.facebook.page_access_token', '');
        }

        try {
            $this->updateEnvSettings([
                'FACEBOOK_ENABLED' => ($validated['enabled'] ?? false) ? 'true' : 'false',
                'FACEBOOK_PAGE_ID' => $validated['page_id'] ?? '',
                'FACEBOOK_PAGE_ACCESS_TOKEN' => $validated['page_access_token'],
                'FACEBOOK_API_VERSION' => $validated['api_version'] ?? 'v18.0',
            ]);

            return redirect()->route('admin.settings.index')
                ->with('success', 'Facebook settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Unable to update .env file. Please check file permissions or update manually.');
        }
    }

    /**
     * Update Twitter settings
     */
    public function updateTwitter(Request $request)
    {
        $validated = $request->validate([
            'enabled' => 'nullable|boolean',
            'bearer_token' => 'nullable|string|max:500',
            'api_version' => 'nullable|string|max:10',
        ]);

        if (empty($validated['bearer_token'])) {
            $validated['bearer_token'] = config('services.twitter.bearer_token', '');
        }

        $this->updateEnvSettings([
            'TWITTER_ENABLED' => ($validated['enabled'] ?? false) ? 'true' : 'false',
            'TWITTER_BEARER_TOKEN' => $validated['bearer_token'],
            'TWITTER_API_VERSION' => $validated['api_version'] ?? '2',
        ]);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Twitter settings updated successfully.');
    }

    /**
     * Update Instagram settings
     */
    public function updateInstagram(Request $request)
    {
        $validated = $request->validate([
            'enabled' => 'nullable|boolean',
            'page_id' => 'nullable|string|max:255',
            'access_token' => 'nullable|string|max:500',
            'api_version' => 'nullable|string|max:10',
        ]);

        if (empty($validated['access_token'])) {
            $validated['access_token'] = config('services.instagram.access_token', '');
        }

        $this->updateEnvSettings([
            'INSTAGRAM_ENABLED' => ($validated['enabled'] ?? false) ? 'true' : 'false',
            'INSTAGRAM_PAGE_ID' => $validated['page_id'] ?? '',
            'INSTAGRAM_ACCESS_TOKEN' => $validated['access_token'],
            'INSTAGRAM_API_VERSION' => $validated['api_version'] ?? 'v18.0',
        ]);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Instagram settings updated successfully.');
    }

    /**
     * Update Threads settings
     */
    public function updateThreads(Request $request)
    {
        $validated = $request->validate([
            'enabled' => 'nullable|boolean',
            'page_id' => 'nullable|string|max:255',
            'access_token' => 'nullable|string|max:500',
            'api_version' => 'nullable|string|max:10',
        ]);

        if (empty($validated['access_token'])) {
            $validated['access_token'] = config('services.threads.access_token', '');
        }

        $this->updateEnvSettings([
            'THREADS_ENABLED' => ($validated['enabled'] ?? false) ? 'true' : 'false',
            'THREADS_PAGE_ID' => $validated['page_id'] ?? '',
            'THREADS_ACCESS_TOKEN' => $validated['access_token'],
            'THREADS_API_VERSION' => $validated['api_version'] ?? 'v18.0',
        ]);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Threads settings updated successfully.');
    }

    /**
     * Helper method to update .env settings
     */
    protected function updateEnvSettings(array $settings)
    {
        $envFile = base_path('.env');
        
        if (file_exists($envFile) && is_writable($envFile)) {
            $envContent = file_get_contents($envFile);
            
            foreach ($settings as $key => $value) {
                $escapedValue = preg_replace('/([\\$`])/', '\\\\$1', $value);
                
                if (preg_match("/^{$key}=.*/m", $envContent)) {
                    $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$escapedValue}", $envContent);
                } else {
                    $envContent = rtrim($envContent) . "\n{$key}={$escapedValue}\n";
                }
            }

            file_put_contents($envFile, $envContent);
            Artisan::call('config:clear');
        } else {
            throw new \Exception('Unable to update .env file. Please check file permissions.');
        }
    }

    /**
     * Test Facebook connection
     */
    public function testFacebook()
    {
        $result = $this->facebookService->verifyToken();
        return response()->json($result);
    }

    /**
     * Test Twitter connection
     */
    public function testTwitter()
    {
        $result = $this->twitterService->verifyToken();
        return response()->json($result);
    }

    /**
     * Test Instagram connection
     */
    public function testInstagram()
    {
        $result = $this->instagramService->verifyToken();
        return response()->json($result);
    }

    /**
     * Test Threads connection
     */
    public function testThreads()
    {
        $result = $this->threadsService->verifyToken();
        return response()->json($result);
    }
}

