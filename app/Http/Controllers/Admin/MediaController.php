<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $allFiles = [];
        $directories = [
            'articles/featured', 
            'articles/og', 
            'articles/twitter', 
            'articles/thumbnails', 
            'profiles/avatars', 
            'profiles/covers',
            'articles/content'
        ];
        
        foreach ($directories as $dir) {
            if (Storage::disk('public')->exists($dir)) {
                $files = Storage::disk('public')->files($dir);
                foreach ($files as $file) {
                    $allFiles[] = [
                        'name' => basename($file),
                        'path' => $file,
                        'url' => Storage::url($file),
                        'size' => Storage::disk('public')->size($file),
                        'last_modified' => Storage::disk('public')->lastModified($file),
                        'directory' => $dir,
                        'extension' => pathinfo($file, PATHINFO_EXTENSION)
                    ];
                }
            }
        }

        // Sort by last modified DESC
        usort($allFiles, function($a, $b) {
            return $b['last_modified'] <=> $a['last_modified'];
        });

        // Pagination
        $perPage = 24;
        $page = $request->get('page', 1);
        $offset = ($page * $perPage) - $perPage;
        
        $paginatedFiles = new LengthAwarePaginator(
            array_slice($allFiles, $offset, $perPage, true),
            count($allFiles),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.media.index', ['files' => $paginatedFiles]);
    }

    public function destroy(Request $request)
    {
        $path = $request->input('path');
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return back()->with('success', 'File deleted successfully.');
        }
        return back()->with('error', 'File not found.');
    }
}
