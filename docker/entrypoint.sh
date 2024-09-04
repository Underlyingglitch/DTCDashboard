#!/bin/sh

# Create the config.js file
cat <<EOF > /opt/apps/laravel/public/config.js
window.__CONFIG__ = {
    VITE_APP_ENV: "${VITE_APP_ENV}",
    VITE_PUSHER_APP_KEY: "${VITE_PUSHER_APP_KEY}",
    VITE_PUSHER_HOST: "${VITE_PUSHER_HOST}",
    VITE_PUSHER_PORT: "${VITE_PUSHER_PORT}",
    VITE_PUSHER_CLUSTER: "${VITE_PUSHER_CLUSTER}"
};
EOF

# Start Nginx
nginx -g 'daemon off;'