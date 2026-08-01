import React, { useState } from 'react';

const LikeButton = ({ initialIsLiked, initialLikesCount, articleId, isLoggedIn }) => {
    const [isLiked, setIsLiked] = useState(initialIsLiked === 'true' || initialIsLiked === true);
    const [likesCount, setLikesCount] = useState(parseInt(initialLikesCount, 10) || 0);
    const [loading, setLoading] = useState(false);

    const toggleLike = async () => {
        if (!isLoggedIn) {
            window.location.href = '/login';
            return;
        }

        if (loading) return;

        setLoading(true);
        
        // Optimistic update
        setIsLiked(!isLiked);
        setLikesCount(isLiked ? likesCount - 1 : likesCount + 1);

        try {
            const response = await fetch(`/articles/${articleId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Accept': 'application/json'
                },
            });

            const data = await response.json();
            
            if (data.success) {
                // Sync with server state just in case
                setIsLiked(data.liked);
                setLikesCount(data.likes_count);
            } else {
                // Revert optimistic update on failure
                setIsLiked(isLiked);
                setLikesCount(likesCount);
                console.error(data.message || 'Error toggling like');
            }
        } catch (error) {
            // Revert optimistic update on failure
            setIsLiked(isLiked);
            setLikesCount(likesCount);
            console.error('Error:', error);
        } finally {
            setLoading(false);
        }
    };

    return (
        <button 
            onClick={toggleLike}
            disabled={loading}
            className={`flex items-center gap-2 px-4 py-2 rounded-full transition-all duration-300 ${
                isLiked 
                ? 'bg-accent/10 text-accent ring-1 ring-accent/30' 
                : 'bg-white dark:!bg-bg-card hover:bg-gray-50 dark:hover:!bg-bg-card-hover text-gray-700 dark:!text-text-secondary border border-gray-200 dark:!border-border-secondary'
            }`}
            style={{ fontFamily: "'Poppins', sans-serif", fontWeight: "500" }}
        >
            <svg 
                className={`w-5 h-5 transition-transform duration-300 ${isLiked ? 'scale-110 fill-current' : 'fill-none'}`} 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
            </svg>
            <span>{likesCount} {likesCount === 1 ? 'Like' : 'Likes'}</span>
        </button>
    );
};

export default LikeButton;
