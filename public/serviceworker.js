var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/offline',
    '/images/icons/icon72x72.png',
    '/images/icons/icon96x96.png',
    '/images/icons/icon128x128.png',
    '/images/icons/icon144x144.png',
    '/images/icons/icon152x152.png',
    '/images/icons/icon192x192.png',
    '/images/icons/icon384x384.png',
    '/images/icons/icon512x512.png',
];

// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return fetch('/build/manifest.json')
                    .then(response => response.json())
                    .then(assets => {
                        // Add the assets from the manifest to the cache
                        for (const key in assets) {
                            if (assets.hasOwnProperty(key)) {
                                if (assets[key].file.startsWith('/'))
                                    filesToCache.push(assets[key].file);
                                else
                                    filesToCache.push('/build/' + assets[key].file);
                                if (assets[key].css) {
                                    assets[key].css.forEach(cssFile => {
                                        if (cssFile.startsWith('/'))
                                            filesToCache.push(cssFile);
                                        else
                                            filesToCache.push('/build/' + cssFile)
                                    });
                                }
                            }
                        }
                        return cache.addAll(filesToCache);
                    });
            })
    )
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        }).then(() => {
            return self.clients.claim();
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});

// Notification
self.addEventListener('push', event => {
    const data = event.data.json();
    self.registration.showNotification(data.title, {
        body: data.body,
        icon: '/images/icons/icon192x192.png',
        // badge: '/images/icons/badge.png'
    });
});