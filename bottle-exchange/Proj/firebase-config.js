// firebase-config.js

const firebaseConfig = {
    apiKey: "AIzaSyAZpMIRnpyNHjVlKsP11K2XpoJaQSzL8ek",
    authDomain: "bottle-exchange-43c31.firebaseapp.com",
    projectId: "bottle-exchange-43c31",
    storageBucket: "bottle-exchange-43c31.firebasestorage.app",
    messagingSenderId: "92568512679",
    appId: "1:92568512679:web:56adf4f4a93a2b67485382",
    measurementId: "G-08L28FBBX6"
};

firebase.initializeApp(firebaseConfig);

// Services
const auth = firebase.auth();
const db = firebase.firestore();

console.log("Firebase Initialized:", firebase.apps.length ? true : false); // Debugging line