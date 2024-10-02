import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: window.__CONFIG__.VITE_REVERB_APP_KEY,
    wsHost: window.__CONFIG__.VITE_REVERB_HOST,
    wsPort: window.__CONFIG__.VITE_REVERB_PORT ?? 80,
    wssPort: window.__CONFIG__.VITE_REVERB_PORT ?? 443,
    forceTLS: (window.__CONFIG__.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
