import { initializeApp } from "firebase/app";
import { getFirestore, collection, addDoc, onSnapshot, query, orderBy, updateDoc, deleteDoc, doc } from "firebase/firestore";

const firebaseConfig = {
    apiKey: "AIzaSyCfuAiP3fGJSIJSlm8VMET3jn-iQIKPk_A",
    authDomain: "darktasks-76dbc.firebaseapp.com",
    projectId: "darktasks-76dbc",
    storageBucket: "darktasks-76dbc.firebasestorage.app",
    messagingSenderId: "812845719593",
    appId: "1:812845719593:web:28cc6d19f0385a295f3c38",
    measurementId: "G-WDKCCT1BSB"
};

const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

export { db, collection, addDoc, onSnapshot, query, orderBy, updateDoc, deleteDoc, doc };
