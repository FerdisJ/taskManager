import { db, collection, addDoc, onSnapshot, query, orderBy, updateDoc, deleteDoc, doc } from './firebase';

window.TaskManager = {
    async addTask(title, description, userId) {
        if (!title) return;
        try {
            await addDoc(collection(db, "tasks"), {
                title,
                description,
                completed: false,
                userId: userId,
                createdAt: new Date()
            });
        } catch (e) {
            console.error("Error adding document: ", e);
        }
    },

    async toggleTask(taskId, completed) {
        const taskRef = doc(db, "tasks", taskId);
        await updateDoc(taskRef, {
            completed: !completed
        });
    },

    async deleteTask(taskId) {
        await deleteDoc(doc(db, "tasks", taskId));
    },

    listenToTasks(userId, callback) {
        const q = query(collection(db, "tasks"), orderBy("createdAt", "desc"));
        return onSnapshot(q, (querySnapshot) => {
            const tasks = [];
            querySnapshot.forEach((doc) => {
                const data = doc.data();
                // We filter by userId in JS for simplicity, 
                // but in production it's better to use Firestore rules and query filters.
                if (data.userId == userId) {
                    tasks.push({ id: doc.id, ...data });
                }
            });
            callback(tasks);
        });
    }
};
