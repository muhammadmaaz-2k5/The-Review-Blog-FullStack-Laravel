import React, { useState } from 'react';

export default function FollowButton({ 
  userId, 
  initialIsFollowing, 
  isLoggedIn 
}) {
  const [isFollowing, setIsFollowing] = useState(initialIsFollowing === 'true' || initialIsFollowing === true);
  const [loading, setLoading] = useState(false);

  const toggleFollow = async () => {
    if (!isLoggedIn) {
      window.location.href = '/login';
      return;
    }

    if (loading) return;

    setLoading(true);

    // Optimistic update
    const previousState = isFollowing;
    setIsFollowing(!isFollowing);

    try {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      
      const response = await fetch(`/profile/${userId}/toggle-follow`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken || '',
          'Accept': 'application/json'
        },
      });

      const data = await response.json();

      if (data.success) {
        setIsFollowing(data.following);
        
        // Update the external followers count element in the DOM
        const followersCountEl = document.getElementById('followersCount');
        if (followersCountEl && data.followers_count !== undefined) {
          followersCountEl.textContent = new Intl.NumberFormat().format(data.followers_count);
        }
      } else {
        setIsFollowing(previousState);
        console.error('Follow toggle failed:', data.message);
      }
    } catch (error) {
      setIsFollowing(previousState);
      console.error('Error toggling follow:', error);
    } finally {
      setLoading(false);
    }
  };

  if (isFollowing) {
    return (
      <button 
        onClick={toggleFollow}
        disabled={loading}
        className="flex-1 py-3 font-bold rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white disabled:opacity-50"
      >
        {loading ? (
          <span className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
        ) : 'Followed'}
      </button>
    );
  }

  return (
    <button 
      onClick={toggleFollow}
      disabled={loading}
      className="flex-1 py-3 font-bold rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 bg-accent hover:bg-red-700 text-white shadow-accent/20 disabled:opacity-50"
    >
      {loading ? (
        <span className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
      ) : 'Follow'}
    </button>
  );
}
