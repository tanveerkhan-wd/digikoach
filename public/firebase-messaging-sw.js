/*self.addEventListener('push', async function(event) {
    event.waitUntil(
        self.registration.showNotification('title', {
          body: 'body'
        })
    );
});*/

importScripts('https://www.gstatic.com/firebasejs/7.24.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.16.1/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
firebase.initializeApp({
  apiKey: "AIzaSyA-7U6wAmJ_OztAJ3kdETYN2Ft9D6mCrHY",
    authDomain: "digikoach-f4c10.firebaseapp.com",
    databaseURL: "https://digikoach-f4c10.firebaseio.com",
    projectId: "digikoach-f4c10",
    storageBucket: "digikoach-f4c10.appspot.com",
    messagingSenderId: "351651372884",
    appId: "1:351651372884:web:47026a90c69d5fc8c072c1",
    measurementId: "G-7F2Q4GK7S1"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
/*messaging.onMessage(function(payload) {
    console.log("Message received. ", payload);
    // NotisElem.innerHTML = NotisElem.innerHTML + JSON.stringify(payload) 
});*/