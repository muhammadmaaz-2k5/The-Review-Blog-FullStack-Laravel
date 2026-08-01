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

function ClerkApp({ signInMount, signUpMount }) {
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
        </ClerkProvider>
    );
}

const mountReactApp = () => {
    const signInMount = document.getElementById('react-clerk-sign-in');
    const signUpMount = document.getElementById('react-clerk-sign-up');

    let appRoot = document.getElementById('clerk-react-app-root');
    if (!appRoot) {
        appRoot = document.createElement('div');
        appRoot.id = 'clerk-react-app-root';
        document.body.appendChild(appRoot);
    }

    const root = createRoot(appRoot);
    root.render(<ClerkApp signInMount={signInMount} signUpMount={signUpMount} />);
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', mountReactApp);
} else {
    mountReactApp();
}
