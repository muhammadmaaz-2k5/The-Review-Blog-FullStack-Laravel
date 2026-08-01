import React, { useEffect, useState } from 'react';
import { createPortal } from 'react-dom';
import { createRoot } from 'react-dom/client';
import { ClerkProvider, SignIn, SignUp, useAuth, useUser } from '@clerk/react';
import axios from 'axios';

const clerkPubKey = import.meta.env.VITE_CLERK_PUBLISHABLE_KEY;

if (!clerkPubKey) {
  console.error("Missing VITE_CLERK_PUBLISHABLE_KEY in .env");
}

function ClerkSync() {
    const { isLoaded, isSignedIn, getToken } = useAuth();
    const { user } = useUser();
    const [synced, setSynced] = useState(false);

    useEffect(() => {
        const checkAndSync = async () => {
            if (!isLoaded) return;
            
            if (isSignedIn && user && !window.LARAVEL_AUTH && !synced) {
                try {
                    const token = await getToken();
                    if (!token) return;

                    const email = user.primaryEmailAddress ? user.primaryEmailAddress.emailAddress : null;
                    const name = user.fullName || user.firstName || 'User';

                    const response = await axios.post('/auth/clerk', {
                        id_token: token,
                        email: email,
                        name: name
                    }, {
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (response.data.success) {
                        setSynced(true);
                        window.location.href = response.data.redirect || '/';
                    }
                } catch (error) {
                    console.error('Failed to sync session with backend', error);
                }
            } else if (isLoaded && !isSignedIn && window.LARAVEL_AUTH && !synced) {
                // User is signed out of Clerk but STILL signed into Laravel -> Log out of Laravel
                try {
                    const response = await axios.post('/logout', {}, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    if (response.status === 200 || response.status === 204) {
                        setSynced(true);
                        window.location.reload();
                    }
                } catch (error) {
                    console.error('Failed to logout of backend', error);
                }
            }
        };

        checkAndSync();
    }, [isLoaded, isSignedIn, user, synced, getToken]);

    return null;
}

import FollowButton from './components/FollowButton';
import LikeButton from './components/LikeButton';
import BookmarkButton from './components/BookmarkButton';
import Comments from './components/Comments';

function ClerkApp({ signInMount, signUpMount, followButtonMount, likeButtonMounts, bookmarkButtonMounts, commentsMount }) {
    // If we are on the login or register page, we shouldn't mount the component 
    // if the user is already signed in and synced with Laravel, because they would be redirected.
    // However, if they are NOT synced, ClerkSync will handle the sync and redirect.
    return (
        <ClerkProvider publishableKey={clerkPubKey}>
            <ClerkSync />
            {signInMount && createPortal(
                <SignIn 
                    routing="path"
                    path="/login"
                    fallbackRedirectUrl="/"
                    signUpUrl="/register"
                />,
                signInMount
            )}
            {signUpMount && createPortal(
                <SignUp 
                    routing="path"
                    path="/register"
                    fallbackRedirectUrl="/"
                    signInUrl="/login"
                />,
                signUpMount
            )}
            {followButtonMount && createPortal(
                <FollowButton 
                    userId={followButtonMount.dataset.userId}
                    initialIsFollowing={followButtonMount.dataset.following}
                    isLoggedIn={followButtonMount.dataset.loggedIn === 'true'}
                />,
                followButtonMount
            )}
            {likeButtonMounts && Array.from(likeButtonMounts).map((mount, index) => createPortal(
                <LikeButton 
                    key={`like-${index}`}
                    articleId={mount.dataset.articleId}
                    initialIsLiked={mount.dataset.isLiked}
                    initialLikesCount={mount.dataset.likesCount}
                    isLoggedIn={mount.dataset.loggedIn === 'true'}
                    variant={mount.dataset.variant || 'main'}
                />,
                mount
            ))}
            {bookmarkButtonMounts && Array.from(bookmarkButtonMounts).map((mount, index) => createPortal(
                <BookmarkButton 
                    key={`bookmark-${index}`}
                    articleId={mount.dataset.articleId}
                    initialIsBookmarked={mount.dataset.isBookmarked}
                    isLoggedIn={mount.dataset.loggedIn === 'true'}
                    variant={mount.dataset.variant || 'main'}
                />,
                mount
            ))}
            {commentsMount && createPortal(
                <Comments 
                    articleId={commentsMount.dataset.articleId}
                    initialComments={JSON.parse(commentsMount.dataset.comments || '[]')}
                    initialCaptchaQuestion={commentsMount.dataset.captchaQuestion}
                    allowComments={commentsMount.dataset.allowComments === 'true'}
                    isLoggedIn={commentsMount.dataset.loggedIn === 'true'}
                    currentUser={JSON.parse(commentsMount.dataset.currentUser || 'null')}
                />,
                commentsMount
            )}
        </ClerkProvider>
    );
}

const mountReactApp = () => {
    const signInMount = document.getElementById('react-clerk-sign-in');
    const signUpMount = document.getElementById('react-clerk-sign-up');
    const followButtonMount = document.getElementById('react-follow-button-root');
    const likeButtonMounts = document.querySelectorAll('.react-like-button-root');
    const bookmarkButtonMounts = document.querySelectorAll('.react-bookmark-button-root');
    const commentsMount = document.getElementById('react-comments-root');

    let appRoot = document.getElementById('clerk-react-app-root');
    if (!appRoot) {
        appRoot = document.createElement('div');
        appRoot.id = 'clerk-react-app-root';
        document.body.appendChild(appRoot);
    }

    const root = createRoot(appRoot);
    root.render(
        <ClerkApp 
            signInMount={signInMount} 
            signUpMount={signUpMount} 
            followButtonMount={followButtonMount} 
            likeButtonMounts={likeButtonMounts.length > 0 ? likeButtonMounts : null}
            bookmarkButtonMounts={bookmarkButtonMounts.length > 0 ? bookmarkButtonMounts : null}
            commentsMount={commentsMount}
        />
    );
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', mountReactApp);
} else {
    mountReactApp();
}
