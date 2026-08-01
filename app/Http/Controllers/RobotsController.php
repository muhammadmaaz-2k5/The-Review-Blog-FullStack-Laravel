<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    /**
     * Generate robots.txt dynamically
     */
    public function index(): Response
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "\nSitemap: " . route('sitemap.index') . "\n";

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}

