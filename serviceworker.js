//Always edit changes in this serviceworker file
//to view changes in other files e.g. html/css.
self.addEventListener("install", e =>{
    e.waitUntil(
        caches.open('static').then(cache =>{
            return cache.addAll(["index.html","styles/index/192px.png"]);
        })
        
    );
});

self.addEventListener("fetch", e =>{
    e.respondWith(
        caches.match(e.request).then(response =>{
            return response || fetch(e.request);
        })
    );

});

self.addEventListener('push', function (event) {
    const options = {
      body: event.data.text(),
    };
  
    event.waitUntil(
      self.registration.showNotification('Your Notification Title', options)
    );
  });
  
  self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    event.waitUntil(
      clients.openWindow('/PWA/report.php') // Open your PWA or a specific URL
    );
  });