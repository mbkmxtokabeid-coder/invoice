
// Import the functions you need from the SDKs you need
// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.13.2/firebase-app.js";
import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging.js";

// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyCSizDxcdeW6kDoqzZjcIXh3kgyHUwAnGM",
  authDomain: "notifdev-b86ea.firebaseapp.com",
  projectId: "notifdev-b86ea",
  storageBucket: "notifdev-b86ea.appspot.com",
  messagingSenderId: "96136881763",
  appId: "1:96136881763:web:136652eaa55c4d2288ba56"
};

// Initialize Firebase
const supportsMessaging =
  window.isSecureContext &&
  'serviceWorker' in navigator &&
  'Notification' in window &&
  'PushManager' in window;

if (supportsMessaging) {
  const app = initializeApp(firebaseConfig);
  const messaging = getMessaging(app);

  getToken(messaging, { vapidKey: 'BMqMkl2XsxFEdpIwRmXicwKuAO8GKg2vXuo3NQ58M5w9dI-d1uC8dIadam3hGBHLEpCgCzx8CX9XuRXiYTucP_E' })
    .then((currentToken) => {
      if (currentToken) {
        storeToken(currentToken);
      }
    })
    .catch((err) => {
      console.warn('Push notification is unavailable in this browser.', err);
    });
}

function storeToken(token) {
  const csrfMeta = document.querySelector('meta[name="csrf-token"]');
  if (!csrfMeta || !window.jQuery) {
    return;
  }

  const csrf = csrfMeta.getAttribute('content');
   $.ajax({
    url: window.location.origin + '/token-notif',
    type: 'POST',
    data: {
      token: token,
    },
    headers: {
      'X-CSRF-TOKEN': csrf
    },
    success: function (response) {
      console.log('Token stored successfully:', response);
    },
    error: function (xhr, status, error) {
      console.error('Error storing token:', xhr.responseText);
    }
  });
}