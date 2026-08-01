<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearFeaturedVideoCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'featured-videos:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the featured video cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Cache::forget('random_featured_video');
        $this->info('Featured video cache cleared successfully.');
        
        return Command::SUCCESS;
    }
}