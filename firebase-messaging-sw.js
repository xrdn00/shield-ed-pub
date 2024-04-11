/*importScripts('https://www.gstatic.com/firebasejs/8.2.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.2.1/firebase-messaging.js');
self.addEventListener("install", e =>{
    e.waitUntil(
        caches.open('static').then(cache =>{
            return cache.addAll(["index.html","styles/index/192px.png","styles/index/style.css"]);
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
    body: "Incoming call from admin",
    icon: "notif_icon.png",
  };

  event.waitUntil(
    self.registration.showNotification('Shield-ED+: Safety and Prevention', options)
  );
});

self.addEventListener('notificationclick', function (event) {
  event.notification.close();
  event.waitUntil(
    clients.openWindow('report.php') // Open your PWA or a specific URL
  );
});

// TODO: Replace with your project's customized code snippet
firebase.initializeApp({
  apiKey: "AIzaSyDO_k8XF0RnKNxjNLhTaIYvk52yT6xOkHY",
  authDomain: "finalpwa-a9b4f.firebaseapp.com",
  projectId: "finalpwa-a9b4f",
  storageBucket: "finalpwa-a9b4f.appspot.com",
  messagingSenderId: "336771751216",
  appId: "1:336771751216:web:3248d79fb70d8f5043bcf5",
  measurementId: "G-SZSQ2M0NTH"
});

const messaging = firebase.messaging();



messaging.onBackgroundMessage(function (payload) {
  console.log(
    "[firebase-messaging-sw.js] Received background message ",
    payload
  );
  // Customize notification here
  const notificationTitle = payload.data.title;
  const notification = {
    body: payload.data.body,
    data: { 
      url: payload.data.url,
      
    },
  };

  self.registration.showNotification(notificationTitle, notification);
});
self.addEventListener("notificationclick", (event) => {
  event.notification.close();
  event.waitUntil(clients.openWindow(event.notification.data.url));
});
*/
//Uncomment to deploy