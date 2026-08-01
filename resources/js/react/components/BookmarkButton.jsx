import React, { useState, useEffect } from 'react';

const BookmarkButton = ({ initialIsBookmarked, articleId, isLoggedIn, variant = 'main' }) => {
    const [isBookmarked, setIsBookmarked] = useState(initialIsBookmarked === 'true' || initialIsBookmarked === true);
    const [loading, setLoading] = useState(false);

    // Sync state across multiple instances of the button (e.g. main and sidebar)
    useEffect(() => {
        const handleBookmarkChange = (e) => {
            if (e.detail.articleId === articleId) {
                setIsBookmarked(e.detail.isBookmarked);
            }
        };

        window.addEventListener('bookmarkStateChanged', handleBookmarkChange);
        return () => window.removeEventListener('bookmarkStateChanged', handleBookmarkChange);
    }, [articleId]);

    const broadcastBookmarkChange = (newIsBookmarked) => {
        window.dispatchEvent(new CustomEvent('bookmarkStateChanged', {
            detail: { articleId, isBookmarked: newIsBookmarked }
        }));
    };

    const toggleBookmark = async () => {
        if (!isLoggedIn) {
            window.location.href = '/login';
            return;
        }

        if (loading) return;

        setLoading(true);
        
        // Optimistic update
        const newIsBookmarked = !isBookmarked;
        setIsBookmarked(newIsBookmarked);
        broadcastBookmarkChange(newIsBookmarked);

        try {
            const response = await fetch(`/articles/${articleId}/bookmark`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Accept': 'application/json'
                },
            });

            if (response.status === 401) {
                window.location.href = '/login';
                return;
            }

            const data = await response.json();
            
            if (data.success) {
                // Sync with server state just in case
                setIsBookmarked(data.bookmarked);
                broadcastBookmarkChange(data.bookmarked);
            } else {
                // Revert optimistic update on failure
                setIsBookmarked(isBookmarked);
                broadcastBookmarkChange(isBookmarked);
                console.error(data.message || 'Error toggling bookmark');
            }
        } catch (error) {
            // Revert optimistic update on failure
            setIsBookmarked(isBookmarked);
            broadcastBookmarkChange(isBookmarked);
            console.error('Error:', error);
        } finally {
            setLoading(false);
        }
    };

    if (variant === 'sidebar') {
        return (
            <button 
                onClick={toggleBookmark}
                disabled={loading}
                title={isBookmarked ? "Remove bookmark" : "Bookmark article"}
                className="w-12 h-12 rounded-full bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary flex items-center justify-center text-gray-500 hover:text-yellow-600 hover:border-yellow-600 transition-all shadow-sm group"
            >
                <svg 
                    className={`w-6 h-6 transition-colors ${isBookmarked ? 'fill-yellow-500 text-yellow-600' : 'group-hover:fill-current'}`} 
                    fill="none"
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                >
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                </svg>
            </button>
        );
    }

    // Default 'main' variant
    return (
        <button 
            onClick={toggleBookmark}
            disabled={loading}
            className={`flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 rounded-lg transition-all text-sm sm:text-base ${
                isBookmarked 
                ? 'bg-yellow-100 text-yellow-600 dark:!bg-yellow-900/20 dark:!text-yellow-400' 
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card'
            }`}
            style={{ fontFamily: "'Poppins', sans-serif", fontWeight: "500" }}
        >
            <svg 
                className={`w-5 h-5 transition-transform duration-300 ${isBookmarked ? 'scale-110 fill-current' : 'fill-none'}`} 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
            </svg>
            <span>{isBookmarked ? 'Bookmarked' : 'Bookmark'}</span>
        </button>
    );
};

export default BookmarkButton;
