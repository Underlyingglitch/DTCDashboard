document.addEventListener('DOMContentLoaded', function () {
    const enableNotificationsButton = document.getElementById('enable-notifications');

    if ('Notification' in window && 'serviceWorker' in navigator) {
        if (Notification.permission === 'default') {
            enableNotificationsButton.style.display = 'block';
        } else {
            enableNotificationsButton.style.display = 'none';
        }

        enableNotificationsButton.addEventListener('click', function () {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    enableNotificationsButton.style.display = 'none';
                    registerServiceWorkerAndSubscribe();
                }
            }).catch(error => {
                console.error('Notification permission error:', error);
            });
        });
    } else {
        console.log('Service Worker or Notifications are not supported');
    }
});

function registerServiceWorkerAndSubscribe() {
    navigator.serviceWorker.ready.then(registration => {
        const vapidPublicKey = window.__CONFIG__.VAPID_PUBLIC_KEY;
        const convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);

        registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: convertedVapidKey
        }).then(subscription => {
            console.log('Subscription successful');
            fetch('/subscribe', {
                method: 'POST',
                body: JSON.stringify(subscription),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Failed to save subscription on server');
                }
                console.log('Subscription saved on server');
            }).catch(error => {
                console.error('Failed to save subscription on server:', error);
            });
        }).catch(error => {
            console.error('Failed to subscribe the user:', error);
        });
    }).catch(error => {
        console.error('Service Worker registration error:', error);
    });
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}