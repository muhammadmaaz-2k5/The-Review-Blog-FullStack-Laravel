import React, { useState, useEffect } from 'react';

const LikeButton = ({ initialIsLiked, initialLikesCount, articleId, isLoggedIn, variant = 'main' }) => {
    const [isLiked, setIsLiked] = useState(initialIsLiked === 'true' || initialIsLiked === true);
    const [likesCount, setLikesCount] = useState(parseInt(initialLikesCount, 10) || 0);
    const [loading, setLoading] = useState(false);

    // Sync state across multiple instances of the button (e.g. main and sidebar)
    useEffect(() => {
        const handleLikeChange = (e) => {
            if (e.detail.articleId === articleId) {
                setIsLiked(e.detail.isLiked);
                setLikesCount(e.detail.likesCount);
            }
        };

        window.addEventListener('likeStateChanged', handleLikeChange);
        return () => window.removeEventListener('likeStateChanged', handleLikeChange);
    }, [articleId]);

    const broadcastLikeChange = (newIsLiked, newLikesCount) => {
        window.dispatchEvent(new CustomEvent('likeStateChanged', {
            detail: { articleId, isLiked: newIsLiked, likesCount: newLikesCount }
        }));
    };

    const toggleLike = async () => {
        if (!isLoggedIn) {
            window.location.href = '/login';
            return;
        }

        if (loading) return;

        setLoading(true);
        
        // Optimistic update
        const newIsLiked = !isLiked;
        const newLikesCount = isLiked ? likesCount - 1 : likesCount + 1;
        setIsLiked(newIsLiked);
        setLikesCount(newLikesCount);
        broadcastLikeChange(newIsLiked, newLikesCount);

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
                broadcastLikeChange(data.liked, data.likes_count);
            } else {
                // Revert optimistic update on failure
                setIsLiked(isLiked);
                setLikesCount(likesCount);
                broadcastLikeChange(isLiked, likesCount);
                console.error(data.message || 'Error toggling like');
            }
        } catch (error) {
            // Revert optimistic update on failure
            setIsLiked(isLiked);
            setLikesCount(likesCount);
            broadcastLikeChange(isLiked, likesCount);
            console.error('Error:', error);
        } finally {
            setLoading(false);
        }
    };

    if (variant === 'sidebar') {
        return (
            <button 
                onClick={toggleLike}
                disabled={loading}
                title={isLiked ? "Unlike article" : "Like article"}
                className="w-12 h-12 rounded-full bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary flex items-center justify-center text-gray-500 hover:text-red-500 hover:border-red-500 transition-all shadow-sm group"
            >
                <svg 
                    className={`w-6 h-6 transition-colors ${isLiked ? 'fill-red-500 text-red-500' : 'group-hover:fill-current'}`} 
                    fill="none"
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                >
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        );
    }

    // Default 'main' variant
    return (
        <button 
            id="likeButton"
            onClick={toggleLike}
            disabled={loading}
            className={`flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 rounded-lg transition-all text-sm sm:text-base ${
                isLiked 
                ? 'bg-red-100 text-red-600 dark:!bg-red-900/20 dark:!text-red-400' 
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card'
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
