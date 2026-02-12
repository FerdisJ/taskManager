<x-app-layout>
    <div class="py-12 bg-slate-950 min-h-screen" x-data="{
        tasks: [],
        newTaskTitle: '',
        newTaskDescription: '',
        userId: {{ auth()->id() }},
        loading: true,
        init() {
            window.TaskManager.listenToTasks(this.userId, (tasks) => {
                this.tasks = tasks;
                this.loading = false;
            });
        },
        async addTask() {
            if (this.newTaskTitle.trim() === '') return;
            await window.TaskManager.addTask(this.newTaskTitle, this.newTaskDescription, this.userId);
            this.newTaskTitle = '';
            this.newTaskDescription = '';
        },
        async toggleTask(task) {
            await window.TaskManager.toggleTask(task.id, task.completed);
        },
        async deleteTask(taskId) {
            if (confirm('¿Estás seguro de eliminar esta tarea?')) {
                await window.TaskManager.deleteTask(taskId);
            }
        }
    }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-extrabold text-white tracking-tight">
                        Mis <span class="text-indigo-500">Tareas</span>
                    </h1>
                    <p class="text-slate-400 mt-2">Gestiona tus actividades con Firebase & Laravel</p>
                </div>
                <div class="text-right">
                    <span class="bg-indigo-500/10 text-indigo-400 px-4 py-2 rounded-full border border-indigo-500/20 text-sm font-medium" x-text="tasks.length + ' tareas'"></span>
                </div>
            </div>

            <!-- Create Task Form -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 mb-8 shadow-xl">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Título de la tarea</label>
                        <input 
                            type="text" 
                            x-model="newTaskTitle" 
                            @keydown.enter="addTask"
                            placeholder="¿Qué hay que hacer?" 
                            class="w-full bg-slate-800 border-slate-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all placeholder:text-slate-500"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Descripción (opcional)</label>
                        <input 
                            type="text" 
                            x-model="newTaskDescription" 
                            @keydown.enter="addTask"
                            placeholder="Detalles adicionales..." 
                            class="w-full bg-slate-800 border-slate-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all placeholder:text-slate-500"
                        >
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button @click="addTask" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 px-8 rounded-xl transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-indigo-600/20">
                        Añadir Tarea
                    </button>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="space-y-4">
                <template x-if="loading">
                    <div class="text-center py-12">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-indigo-500 mb-4"></div>
                        <p class="text-slate-500">Cargando tus tareas de la nube...</p>
                    </div>
                </template>

                <template x-if="!loading && tasks.length === 0">
                    <div class="bg-slate-900/50 border border-dashed border-slate-800 rounded-2xl py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-700 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <p class="text-slate-500 text-lg">No hay tareas pendientes. ¡Buen trabajo!</p>
                    </div>
                </template>

                <template x-for="task in tasks" :key="task.id">
                    <div 
                        class="group bg-slate-900 border border-slate-800 rounded-2xl p-4 flex items-center justify-between transition-all hover:border-slate-700 hover:shadow-2xl hover:shadow-indigo-500/5"
                        :class="task.completed ? 'opacity-60' : ''"
                    >
                        <div class="flex items-center space-x-4 flex-1">
                            <button 
                                @click="toggleTask(task)" 
                                class="flex-shrink-0 w-7 h-7 rounded-full border-2 transition-all flex items-center justify-center"
                                :class="task.completed ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-700 hover:border-indigo-500'"
                            >
                                <svg x-show="task.completed" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <div class="overflow-hidden">
                                <h3 class="text-lg font-semibold text-white truncate" :class="task.completed ? 'line-through text-slate-500' : ''" x-text="task.title"></h3>
                                <p class="text-slate-400 text-sm truncate" x-show="task.description" x-text="task.description"></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2 ml-4">
                            <button @click="deleteTask(task.id)" class="p-2 text-slate-600 hover:text-red-400 transition-colors opacity-0 group-hover:opacity-100">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-app-layout>
