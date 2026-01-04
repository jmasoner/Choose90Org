/**
 * Firebase Authentication Helper
 * 
 * Handles Firebase authentication and WordPress sync
 */

class Choose90FirebaseAuth {
    constructor(config) {
        this.config = config;
        this.auth = null;
        this.user = null;
        this.initialized = false;
        this.userSynced = false; // Track if user has been synced to WordPress
        this.redirectResultChecked = false; // Prevent multiple calls to getRedirectResult
    }

    /**
     * Initialize Firebase Auth
     */
    async init() {
        if (this.initialized) {
            return;
        }

        try {
            // Import Firebase modules (using CDN)
            if (typeof firebase === 'undefined') {
                console.error('Firebase SDK not loaded. Please include Firebase scripts.');
                return false;
            }

            // Initialize Firebase App
            if (!firebase.apps.length) {
                firebase.initializeApp(this.config);
            }

            // Initialize Auth
            this.auth = firebase.auth();
            
            // Set persistence to LOCAL (survives page refreshes)
            try {
                await this.auth.setPersistence(firebase.auth.Auth.Persistence.LOCAL);
                console.log('Firebase auth persistence set to LOCAL');
            } catch (error) {
                console.warn('Could not set auth persistence:', error);
            }
            
            this.initialized = true;

            // CRITICAL: Handle redirect result FIRST, before setting up onAuthStateChanged
            // This must happen immediately to catch the redirect before Edge clears state
            // Don't await - let it run in parallel, but call it immediately
            // Wrap in try-catch to ensure errors don't prevent initialization from completing
            try {
                this.handleAuthRedirect().catch(error => {
                    console.error('Error in handleAuthRedirect:', error);
                });
            } catch (error) {
                console.error('Error calling handleAuthRedirect:', error);
            }

            // Check current auth state immediately (in case user is already signed in)
            // This helps catch cases where Edge hasn't cleared state yet
            // Don't await this - let it run in background so init() can return quickly
            try {
                const currentUser = this.auth.currentUser;
                if (currentUser && !this.userSynced) {
                    console.log('User already authenticated on page load:', currentUser.email);
                    this.user = currentUser;
                    this.userSynced = true;
                    
                    // Check if this is from a redirect
                    const redirectUrl = sessionStorage.getItem('choose90_redirect_url') || '/';
                    const additionalData = JSON.parse(sessionStorage.getItem('choose90_additional_data') || '{}');
                    
                    // Sync with WordPress immediately (don't await - run in background)
                    this.syncWithWordPress(currentUser, additionalData).then(syncResult => {
                        if (syncResult.success) {
                            console.log('WordPress sync successful from currentUser!');
                            window.dispatchEvent(new CustomEvent('choose90:auth:success', {
                                detail: syncResult
                            }));
                            // Clear stored data
                            sessionStorage.removeItem('choose90_additional_data');
                            sessionStorage.removeItem('choose90_redirect_url');
                        }
                    }).catch(error => {
                        console.error('WordPress sync error from currentUser:', error);
                        this.userSynced = false; // Reset so onAuthStateChanged can try
                    });
                }
            } catch (error) {
                console.error('Error checking currentUser:', error);
            }

            // Set up auth state listener (will also catch redirect sign-ins)
            // This will catch the user when they sign in via redirect
            this.auth.onAuthStateChanged(async (user) => {
                this.user = user;
                
                // If user just signed in and we haven't synced yet, sync with WordPress
                if (user && !this.userSynced) {
                    console.log('User signed in via onAuthStateChanged:', user.email);
                    this.userSynced = true;
                    
                    // Check if this is from a redirect (has redirect URL in sessionStorage)
                    const redirectUrl = sessionStorage.getItem('choose90_redirect_url') || sessionStorage.getItem('choose90_login_redirect') || '/';
                    const additionalData = JSON.parse(sessionStorage.getItem('choose90_additional_data') || '{}');
                    
                    // Clear stored data
                    sessionStorage.removeItem('choose90_additional_data');
                    sessionStorage.removeItem('choose90_redirect_url');
                    sessionStorage.removeItem('choose90_login_redirect');
                    
                    // Sync with WordPress
                    try {
                        console.log('Syncing with WordPress from onAuthStateChanged...');
                        const syncResult = await this.syncWithWordPress(user, additionalData);
                        console.log('WordPress sync result:', syncResult);
                        
                        if (syncResult.success) {
                            console.log('WordPress sync successful!');
                            // Trigger success event
                            window.dispatchEvent(new CustomEvent('choose90:auth:success', {
                                detail: syncResult
                            }));
                            
                            // Redirect if needed
                            if (window.location.pathname === '/pledge/' && redirectUrl !== window.location.href) {
                                setTimeout(() => {
                                    window.location.href = redirectUrl;
                                }, 100);
                            }
                        }
                    } catch (error) {
                        console.error('WordPress sync error in onAuthStateChanged:', error);
                    }
                } else {
                    this.userSynced = false;
                }
                
                this.onAuthStateChanged(user);
            });

            return true;
        } catch (error) {
            console.error('Firebase initialization error:', error);
            return false;
        }
    }

    /**
     * Handle OAuth redirect result
     * Called when user returns from Google/Facebook OAuth
     * Must be called IMMEDIATELY on page load to catch redirect before Edge clears state
     */
    async handleAuthRedirect() {
        try {
            // Only check redirect result once - use a flag to prevent multiple calls
            if (this.redirectResultChecked) {
                console.log('Redirect result already checked, skipping...');
                return;
            }
            this.redirectResultChecked = true;
            
            // Check if we're returning from a redirect (URL might have Firebase params)
            const urlParams = new URLSearchParams(window.location.search);
            const hasFirebaseParams = urlParams.has('apiKey') || urlParams.has('authType') || 
                                     window.location.hash.includes('__/auth/handler');
            
            console.log('Checking for redirect result...', {
                hasFirebaseParams,
                currentUrl: window.location.href,
                referrer: document.referrer
            });
            
            // Call getRedirectResult IMMEDIATELY - don't wait for anything
            // Edge may clear state quickly, so we need to process this ASAP
            const result = await this.auth.getRedirectResult();
            console.log('Redirect result:', {
                hasUser: !!result.user,
                hasError: !!result.error,
                userEmail: result.user?.email,
                errorCode: result.error?.code,
                errorMessage: result.error?.message
            });
            
            if (result.user) {
                console.log('User found in redirect result:', result.user.email);
                // User successfully signed in via redirect
                // Note: onAuthStateChanged will also fire and handle the sync
                // This is just a backup in case onAuthStateChanged doesn't fire
                const additionalData = JSON.parse(sessionStorage.getItem('choose90_additional_data') || '{}');
                let redirectUrl = sessionStorage.getItem('choose90_redirect_url') || sessionStorage.getItem('choose90_login_redirect') || '/';
                
                console.log('Additional data:', additionalData);
                console.log('Redirect URL:', redirectUrl);
                
                // Don't clear stored data here - let onAuthStateChanged handle it
                // This prevents double syncing
                
                // Sync with WordPress
                if (!this.userSynced) {
                    console.log('Syncing with WordPress from redirect result...');
                    this.userSynced = true;
                    const syncResult = await this.syncWithWordPress(result.user, additionalData);
                    console.log('WordPress sync result:', syncResult);
                    
                    if (syncResult.success) {
                        console.log('WordPress sync successful!');
                        // Clear stored data
                        sessionStorage.removeItem('choose90_additional_data');
                        sessionStorage.removeItem('choose90_redirect_url');
                        sessionStorage.removeItem('choose90_login_redirect');
                        
                        // Trigger success event
                        window.dispatchEvent(new CustomEvent('choose90:auth:success', {
                            detail: syncResult
                        }));
                        
                        // Redirect to original page or success page
                        // Small delay to ensure WordPress cookie is set
                        setTimeout(() => {
                            window.location.href = redirectUrl;
                        }, 100);
                    } else {
                        console.error('WordPress sync failed:', syncResult.error);
                        this.userSynced = false;
                        throw new Error(syncResult.error || 'Failed to sync with WordPress');
                    }
                } else {
                    console.log('User already synced, skipping...');
                }
            } else if (result.error) {
                // Handle redirect errors
                console.error('Firebase redirect error:', result.error);
                const errorCode = result.error.code || 'unknown';
                const errorMessage = result.error.message || 'Authentication failed';
                
                // Log detailed error for debugging
                console.error('Error details:', {
                    code: errorCode,
                    message: errorMessage,
                    credential: result.error.credential,
                    email: result.error.email
                });
                
                // Show user-friendly error
                let userMessage = 'Sign-in failed: ';
                if (errorCode === 'auth/unauthorized-domain') {
                    userMessage += 'This domain is not authorized. Please contact support.';
                } else if (errorCode === 'auth/internal-error') {
                    userMessage += 'OAuth configuration error. Please check Firebase Console settings.';
                } else {
                    userMessage += errorMessage;
                }
                
                alert(userMessage);
            } else {
                // No user and no error - this is normal if not returning from a redirect
                console.log('No redirect result (normal if not returning from OAuth)');
            }
        } catch (error) {
            console.error('Redirect result error:', error);
            console.error('Error stack:', error.stack);
            console.error('Error details:', {
                name: error.name,
                message: error.message,
                code: error.code
            });
            
            // Clear stored data on error
            sessionStorage.removeItem('choose90_additional_data');
            sessionStorage.removeItem('choose90_redirect_url');
            sessionStorage.removeItem('choose90_login_redirect');
            
            // Show detailed error to user
            let userMessage = 'Sign-in failed: ';
            if (error.code === 'auth/unauthorized-domain') {
                userMessage += 'Domain not authorized. Please add choose90.org to Firebase authorized domains.';
            } else if (error.code === 'auth/internal-error') {
                userMessage += 'OAuth configuration issue. Please check OAuth consent screen in Google Cloud Console.';
            } else {
                userMessage += (error.message || 'Please try again');
            }
            
            alert(userMessage);
        }
    }

    /**
     * Handle auth state changes
     */
    onAuthStateChanged(user) {
        if (user) {
            // User is signed in
            this.syncWithWordPress(user);
        } else {
            // User is signed out
            console.log('User signed out');
        }
    }

    /**
     * Sign in with Google
     * Uses popup method to avoid Edge bounce tracking issues
     */
    async signInWithGoogle(additionalData = {}) {
        if (!this.initialized) {
            await this.init();
        }

        try {
            const provider = new firebase.auth.GoogleAuthProvider();
            provider.addScope('profile');
            provider.addScope('email');
            
            // Set custom parameters for the OAuth
            provider.setCustomParameters({
                'prompt': 'select_account'
            });
            
            // Store additional data for after sign-in
            if (Object.keys(additionalData).length > 0) {
                sessionStorage.setItem('choose90_additional_data', JSON.stringify(additionalData));
            }
            
            // Use popup instead of redirect - avoids Edge bounce tracking issues
            const result = await this.auth.signInWithPopup(provider);
            
            // User signed in successfully via popup
            console.log('Google sign-in successful via popup:', result.user.email);
            
            // Sync with WordPress immediately
            const syncResult = await this.syncWithWordPress(result.user, additionalData);
            
            if (syncResult.success) {
                console.log('WordPress sync successful!');
                // Clear stored data
                sessionStorage.removeItem('choose90_additional_data');
                sessionStorage.removeItem('choose90_redirect_url');
                
                // Trigger success event
                window.dispatchEvent(new CustomEvent('choose90:auth:success', {
                    detail: syncResult
                }));
                
                // Reload page to show logged-in state
                window.location.reload();
            } else {
                throw new Error(syncResult.error || 'Failed to sync with WordPress');
            }
            
        } catch (error) {
            console.error('Google sign-in error:', error);
            console.error('Error details:', {
                code: error.code,
                message: error.message,
                email: error.email,
                credential: error.credential
            });
            
            // Provide more helpful error messages
            if (error.code === 'auth/unauthorized-domain') {
                throw new Error('Domain not authorized. Please add choose90.org to Firebase authorized domains in Firebase Console → Authentication → Settings.');
            } else if (error.code === 'auth/internal-error') {
                throw new Error('OAuth configuration error. Please configure OAuth consent screen in Google Cloud Console → APIs & Services → OAuth consent screen.');
            }
            
            throw error;
        }
    }

    /**
     * Sign in with Facebook
     * Uses popup method to avoid Edge bounce tracking issues
     */
    async signInWithFacebook(additionalData = {}) {
        if (!this.initialized) {
            await this.init();
        }

        try {
            const provider = new firebase.auth.FacebookAuthProvider();
            provider.addScope('email');
            provider.addScope('public_profile');
            
            // Store additional data for after sign-in
            if (Object.keys(additionalData).length > 0) {
                sessionStorage.setItem('choose90_additional_data', JSON.stringify(additionalData));
            }
            
            // Use popup instead of redirect - avoids Edge bounce tracking issues
            const result = await this.auth.signInWithPopup(provider);
            
            // User signed in successfully via popup
            console.log('Facebook sign-in successful via popup:', result.user.email);
            
            // Sync with WordPress immediately
            const syncResult = await this.syncWithWordPress(result.user, additionalData);
            
            if (syncResult.success) {
                console.log('WordPress sync successful!');
                // Clear stored data
                sessionStorage.removeItem('choose90_additional_data');
                sessionStorage.removeItem('choose90_redirect_url');
                
                // Trigger success event
                window.dispatchEvent(new CustomEvent('choose90:auth:success', {
                    detail: syncResult
                }));
                
                // Reload page to show logged-in state
                window.location.reload();
            } else {
                throw new Error(syncResult.error || 'Failed to sync with WordPress');
            }
            
        } catch (error) {
            console.error('Facebook sign-in error:', error);
            console.error('Error details:', {
                code: error.code,
                message: error.message,
                email: error.email,
                credential: error.credential
            });
            
            // Provide more helpful error messages
            if (error.code === 'auth/unauthorized-domain') {
                throw new Error('Domain not authorized. Please add choose90.org to Firebase authorized domains in Firebase Console → Authentication → Settings.');
            } else if (error.code === 'auth/internal-error') {
                throw new Error('OAuth configuration error. Please configure OAuth consent screen in Google Cloud Console → APIs & Services → OAuth consent screen.');
            }
            
            throw error;
        }
    }

    /**
     * Sign in with Email/Password
     */
    async signInWithEmail(email, password) {
        if (!this.initialized) {
            await this.init();
        }

        try {
            const result = await this.auth.signInWithEmailAndPassword(email, password);
            return await this.syncWithWordPress(result.user);
        } catch (error) {
            console.error('Email sign-in error:', error);
            throw error;
        }
    }

    /**
     * Create account with Email/Password
     */
    async createAccountWithEmail(email, password, additionalData = {}) {
        if (!this.initialized) {
            await this.init();
        }

        try {
            const result = await this.auth.createUserWithEmailAndPassword(email, password);
            
            // Update profile if name provided
            if (additionalData.full_name || additionalData.screen_name) {
                await result.user.updateProfile({
                    displayName: additionalData.full_name || additionalData.screen_name || email
                });
            }

            return await this.syncWithWordPress(result.user, additionalData);
        } catch (error) {
            console.error('Email sign-up error:', error);
            throw error;
        }
    }

    /**
     * Sign out from both Firebase and WordPress
     */
    async signOut() {
        if (!this.initialized) {
            // Even if Firebase isn't initialized, try WordPress logout
            try {
                const response = await fetch('/api/logout.php', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                window.location.href = data.redirect_to || '/';
                return;
            } catch (error) {
                console.error('WordPress logout error:', error);
                window.location.href = '/';
                return;
            }
        }

        try {
            // Sign out from Firebase
            await this.auth.signOut();
            
            // Also sign out from WordPress
            try {
                const response = await fetch('/api/logout.php', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                window.location.href = data.redirect_to || '/login/';
            } catch (wpError) {
                console.error('WordPress logout error:', wpError);
                // Even if WordPress logout fails, redirect
                window.location.href = '/login/';
            }
        } catch (error) {
            console.error('Firebase sign out error:', error);
            // Try WordPress logout even if Firebase fails
            try {
                const response = await fetch('/api/logout.php', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                window.location.href = data.redirect_to || '/';
            } catch (wpError) {
                console.error('WordPress logout error:', wpError);
                window.location.href = '/';
            }
        }
    }

    /**
     * Sync Firebase user with WordPress
     */
    async syncWithWordPress(firebaseUser, additionalData = {}) {
        try {
            console.log('Getting Firebase ID token...');
            // Get Firebase ID token
            const token = await firebaseUser.getIdToken();
            console.log('Got Firebase token (length:', token.length, ')');

            console.log('Calling WordPress API...');
            // Send to WordPress API
            const response = await fetch('/api/firebase-auth-callback.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'include', // Important for cookies
                body: JSON.stringify({
                    token: token,
                    additional_data: additionalData
                })
            });

            console.log('API response status:', response.status);
            const data = await response.json();
            console.log('API response data:', data);

            if (data.success) {
                console.log('WordPress sync successful!');
                // Trigger custom event for other scripts
                window.dispatchEvent(new CustomEvent('choose90:auth:success', {
                    detail: data
                }));

                return data;
            } else {
                console.error('WordPress sync failed:', data.error);
                throw new Error(data.error || 'Failed to sync with WordPress');
            }
        } catch (error) {
            console.error('WordPress sync error:', error);
            throw error;
        }
    }

    /**
     * Get current user
     */
    getCurrentUser() {
        return this.user;
    }

    /**
     * Check if user is authenticated
     */
    isAuthenticated() {
        return this.user !== null;
    }
}

// Initialize global instance when config is available
window.Choose90FirebaseAuth = Choose90FirebaseAuth;

// Auto-initialize if config is in window
if (window.choose90FirebaseConfig) {
    window.choose90Auth = new Choose90FirebaseAuth(window.choose90FirebaseConfig);
    window.choose90Auth.init();
}
