<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CommentController extends Controller
{
    /**
     * Generate a simple math CAPTCHA
     */
    private function generateCaptcha()
    {
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $answer = $num1 + $num2;
        Session::put('comment_captcha_answer', $answer);
        return ['question' => "$num1 + $num2", 'answer' => $answer];
    }
    /**
     * Store a newly created comment
     */
    public function store(Request $request, Article $article)
    {
        // Check if comments are allowed for this article
        if (!$article->allow_comments) {
            return back()->with('error', 'Comments are disabled for this article.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'captcha_answer' => 'required|integer',
        ]);

        // Validate CAPTCHA
        $correctAnswer = Session::get('comment_captcha_answer');
        if (!$correctAnswer || (int)$request->captcha_answer !== (int)$correctAnswer) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'CAPTCHA answer is incorrect. Please try again.',
                    'errors' => ['captcha_answer' => ['CAPTCHA answer is incorrect.']]
                ], 422);
            }
            return back()->withErrors(['captcha_answer' => 'CAPTCHA answer is incorrect. Please try again.'])->withInput();
        }

        // Clear CAPTCHA from session after successful validation
        Session::forget('comment_captcha_answer');

        // If user is authenticated, use their user_id but allow them to override name/email
        if (Auth::check()) {
            $validated['user_id'] = Auth::id();
            // Use submitted name/email (allows users to use different name/email if they want)
            $validated['name'] = $validated['name'] ?? Auth::user()->name;
            $validated['email'] = $validated['email'] ?? Auth::user()->email;
        }

        $validated['article_id'] = $article->id;
        $validated['status'] = config('app.comment_moderation', false) ? 'pending' : 'approved';
        $validated['ip_address'] = $request->ip();
        $validated['user_agent'] = $request->userAgent();

        $comment = Comment::create($validated);
        $comment->load('user');

        if ($request->expectsJson() || $request->ajax()) {
            if ($validated['status'] === 'pending') {
                return response()->json([
                    'success' => true,
                    'message' => 'Your comment has been submitted and is awaiting moderation.',
                    'pending' => true
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Your comment has been posted successfully!',
                'comment' => [
                    'id' => $comment->id,
                    'name' => $comment->user ? $comment->user->name : $comment->name,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'is_author' => $comment->user && $comment->user->isAuthor(),
                    'avatar' => $comment->user && $comment->user->avatar ? $comment->user->avatar : null,
                ]
            ]);
        }

        if ($validated['status'] === 'pending') {
            return back()->with('success', 'Your comment has been submitted and is awaiting moderation.');
        }

        return back()->with('success', 'Your comment has been posted successfully!');
    }

    /**
     * Store a reply to a comment
     */
    public function reply(Request $request, Article $article, Comment $comment)
    {
        // Check if comments are allowed for this article
        if (!$article->allow_comments) {
            return back()->with('error', 'Comments are disabled for this article.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'captcha_answer' => 'required|integer',
        ]);

        // Validate CAPTCHA
        $correctAnswer = Session::get('comment_captcha_answer');
        if (!$correctAnswer || (int)$request->captcha_answer !== (int)$correctAnswer) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'CAPTCHA answer is incorrect. Please try again.',
                    'errors' => ['captcha_answer' => ['CAPTCHA answer is incorrect.']]
                ], 422);
            }
            return back()->withErrors(['captcha_answer' => 'CAPTCHA answer is incorrect. Please try again.'])->withInput();
        }

        // Clear CAPTCHA from session after successful validation
        Session::forget('comment_captcha_answer');

        // If user is authenticated, use their user_id but allow them to override name/email
        if (Auth::check()) {
            $validated['user_id'] = Auth::id();
            // Use submitted name/email (allows users to use different name/email if they want)
            $validated['name'] = $validated['name'] ?? Auth::user()->name;
            $validated['email'] = $validated['email'] ?? Auth::user()->email;
        }

        $validated['article_id'] = $article->id;
        $validated['parent_id'] = $comment->id;
        $validated['status'] = config('app.comment_moderation', false) ? 'pending' : 'approved';
        $validated['ip_address'] = $request->ip();
        $validated['user_agent'] = $request->userAgent();

        $reply = Comment::create($validated);
        $reply->load('user');

        if ($request->expectsJson() || $request->ajax()) {
            if ($validated['status'] === 'pending') {
                return response()->json([
                    'success' => true,
                    'message' => 'Your reply has been submitted and is awaiting moderation.',
                    'pending' => true
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Your reply has been posted successfully!',
                'reply' => [
                    'id' => $reply->id,
                    'name' => $reply->user ? $reply->user->name : $reply->name,
                    'content' => $reply->content,
                    'created_at' => $reply->created_at->diffForHumans(),
                    'is_author' => $reply->user && $reply->user->isAuthor(),
                    'avatar' => $reply->user && $reply->user->avatar ? $reply->user->avatar : null,
                    'parent_id' => $reply->parent_id,
                ]
            ]);
        }

        if ($validated['status'] === 'pending') {
            return back()->with('success', 'Your reply has been submitted and is awaiting moderation.');
        }

        return back()->with('success', 'Your reply has been posted successfully!');
    }

    /**
     * Generate and return a new CAPTCHA
     */
    public function refreshCaptcha()
    {
        $captcha = $this->generateCaptcha();
        return response()->json([
            'success' => true,
            'question' => $captcha['question'],
        ]);
    }
}

