<div class="flex items-start gap-3">
    <div class="flex-shrink-0">
        @if($reply->user && $reply->user->avatar)
            <img src="{{ $reply->user->avatar }}" alt="{{ $reply->user->name }}" class="w-8 h-8 rounded-full object-cover">
        @else
            <div class="w-8 h-8 rounded-full bg-accent flex items-center justify-center text-white font-semibold text-xs">
                {{ strtoupper(substr($reply->user ? $reply->user->name : $reply->name, 0, 1)) }}
            </div>
        @endif
    </div>
    <div class="flex-1">
        <div class="flex items-center gap-2 mb-1">
            <h5 class="font-semibold text-gray-900 dark:!text-white text-sm" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                {{ $reply->user ? $reply->user->name : $reply->name }}
            </h5>
            @if($reply->user && $reply->user->isAuthor())
                <span class="px-1.5 py-0.5 bg-blue-100 text-blue-800 rounded text-xs dark:!bg-blue-900/20 dark:!text-blue-400" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                    Author
                </span>
            @endif
            <span class="text-xs text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                • {{ $reply->created_at->diffForHumans() }}
            </span>
        </div>
        <p class="text-gray-700 dark:!text-text-primary text-sm whitespace-pre-wrap mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400; line-height: 1.5;">
            {{ $reply->content }}
        </p>
        
        <!-- Reply Button -->
        <button onclick="showReplyForm({{ $reply->id }})" class="text-xs text-accent hover:text-accent-light font-semibold transition-colors mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
            Reply
        </button>

        <!-- Reply Form (Hidden by default) -->
        <div id="reply-form-{{ $reply->id }}" class="hidden mt-2 pt-2 border-t border-gray-200 dark:!border-border-secondary">
            <form action="{{ route('comments.reply', [$article, $reply]) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                    <div>
                        <label for="reply-name-{{ $reply->id }}" class="block text-xs font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="reply-name-{{ $reply->id }}" 
                               class="reply-name-input w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white" 
                               value="{{ auth()->check() ? auth()->user()->name : '' }}"
                               required
                               placeholder="Your name">
                    </div>
                    <div>
                        <label for="reply-email-{{ $reply->id }}" class="block text-xs font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="reply-email-{{ $reply->id }}" 
                               class="reply-email-input w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white" 
                               value="{{ auth()->check() ? auth()->user()->email : '' }}"
                               required
                               placeholder="your@email.com">
                    </div>
                </div>

                <div class="mb-3">
                    <textarea name="content" rows="2" required
                              class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                              placeholder="Write your reply..."></textarea>
                </div>

                <div class="mb-3">
                    <label for="reply-captcha-{{ $reply->id }}" class="block text-xs font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        CAPTCHA: {{ $captchaQuestion ?? '0 + 0' }} = ? <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="captcha_answer" id="reply-captcha-{{ $reply->id }}" required
                           class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                           placeholder="Enter the answer">
                    @error('captcha_answer')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-3 py-1.5 bg-accent hover:bg-accent-light text-white font-semibold rounded-lg transition-all text-xs" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Post Reply
                    </button>
                    <button type="button" onclick="hideReplyForm({{ $reply->id }})" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-all text-xs dark:!bg-bg-card-hover dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Nested Replies (replies to replies) -->
        @if($reply->replies->count() > 0)
            <div class="mt-2 ml-4 space-y-2 border-l-2 border-gray-200 dark:!border-border-secondary pl-3">
                @foreach($reply->replies as $nestedReply)
                    @include('articles._comment_reply', ['reply' => $nestedReply, 'article' => $article, 'level' => $level + 1])
                @endforeach
            </div>
        @endif
    </div>
</div>

