// PWA Service Worker Registration
(function() {
    'use strict';

    // Check if service workers are supported
    if ('serviceWorker' in navigator) {
        // Register service worker when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', registerServiceWorker);
        } else {
            registerServiceWorker();
        }
    }

    function registerServiceWorker() {
        navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                console.log('[PWA] Service Worker registered successfully:', registration.scope);

                // Check for updates periodically
                setInterval(() => {
                    registration.update();
                }, 60000); // Check every minute

                // Handle updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    if (newWorker) {
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                // New service worker available
                                showUpdateNotification();
                            }
                        });
                    }
                });
            })
            .catch((error) => {
                console.error('[PWA] Service Worker registration failed:', error);
            });

        // Handle controller change (new service worker activated)
        navigator.serviceWorker.addEventListener('controllerchange', () => {
            console.log('[PWA] New service worker activated, reloading page...');
            window.location.reload();
        });
    }

    function showUpdateNotification() {
        // You can customize this to show a notification to the user
        // For now, we'll just log it
        console.log('[PWA] New version available. Reload to update.');
        
        // Optional: Show a toast notification
        if (typeof window.showToast === 'function') {
            window.showToast('Nova versão disponível! Recarregue a página para atualizar.', 'info');
        }
    }

    // Handle install prompt
    let deferredPrompt;
    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent the mini-infobar from appearing on mobile
        e.preventDefault();
        // Stash the event so it can be triggered later
        deferredPrompt = e;
        // Show install button or notification
        showInstallPrompt();
    });

    function showInstallPrompt() {
        // You can customize this to show an install button
        console.log('[PWA] Install prompt available');
        
        // Optional: Create and show install button
        const installButton = document.createElement('button');
        installButton.textContent = 'Instalar App';
        installButton.className = 'fixed bottom-20 right-4 bg-churrasco-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        installButton.style.display = 'none'; // Hidden by default, show when needed
        installButton.addEventListener('click', installApp);
        document.body.appendChild(installButton);
        
        // Show button after a delay
        setTimeout(() => {
            installButton.style.display = 'block';
        }, 3000);
    }

    function installApp() {
        if (deferredPrompt) {
            // Show the install prompt
            deferredPrompt.prompt();
            
            // Wait for the user to respond
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('[PWA] User accepted the install prompt');
                } else {
                    console.log('[PWA] User dismissed the install prompt');
                }
                deferredPrompt = null;
            });
        }
    }

    // Expose install function globally
    window.installPWA = installApp;
})();




















