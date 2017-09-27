self.addEventListener('install', e => {
  e.waitUntil(
    caches.open('amptopwa-cache').then(cache =>
      {
        return cache.addAll(['pwa.html', 'pwa.js', 'style.css', 'amp-0.html', 'amp-1.html', 'amp-2.html'])
      })
  )
});

self.addEventListener('fetch', e => {
    e.respondWith(
      caches.match(e.request).then(response => {
        return response || fetch(e.request);
      })
    );
});