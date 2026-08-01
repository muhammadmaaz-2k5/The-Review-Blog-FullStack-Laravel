<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ArticleLikeController extends Controller
{
    /**
     * Toggle like on an article
     */
    public function toggle(Request $request, Article $article)
    {
        try {
            
            $userId = Auth::id();
            $ipAddress = request()->ip();

            // Check if already liked
            $like = ArticleLike::where('article_id', $article->id)
                ->where(function($query) use ($userId, $ipAddress) {
                    if ($userId) {
                        $query->where('user_id', $userId);
                    } else {
                        $query->whereNull('user_id')
                              ->where('ip_address', $ipAddress);
                    }
                })
                ->first();

            if ($like) {
                // Unlike
                $like->delete();
                $liked = false;
            } else {
                // Like - check for duplicates first
                $existingLike = ArticleLike::where('article_id', $article->id);
                if ($userId) {
                    $existingLike = $existingLike->where('user_id', $userId);
                } else {
                    $existingLike = $existingLike->whereNull('user_id')
                                                 ->where('ip_address', $ipAddress);
                }
                
                if (!$existingLike->exists()) {
                    ArticleLike::create([
                        'article_id' => $article->id,
                        'user_id' => $userId,
                        'ip_address' => $userId ? null : $ipAddress,
                    ]);
                }
                $liked = true;
            }

            // Refresh to get accurate count
            $article->refresh();
            $likesCount = $article->likes()->count();

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount,
            ]);
        } catch (\Exception $e) {
            Log::error('Like error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your like.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}

