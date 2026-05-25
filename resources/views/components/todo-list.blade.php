<x-ui.card class="p-6 border-2 border-primary/10 shadow-sm flex flex-col h-full" 
           x-data="{ 
                tasks: [], 
                newTaskTitle: '', 
                loading: true,
                async fetchTasks() {
                    this.loading = true;
                    try {
                        const response = await fetch('/api/tasks', {
                            headers: { 'Accept': 'application/json' }
                        });
                        this.tasks = await response.json();
                    } catch (error) {
                        console.error('Failed to load tasks', error);
                    } finally {
                        this.loading = false;
                    }
                },
                async addTask() {
                    if (!this.newTaskTitle.trim()) return;
                    try {
                        const response = await fetch('/api/tasks', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ title: this.newTaskTitle })
                        });
                        const newTask = await response.json();
                        this.tasks.unshift(newTask);
                        this.newTaskTitle = '';
                    } catch (error) {
                        alert('Échec de l\'ajout de la tâche');
                    }
                },
                async toggleTask(task) {
                    const originalStatus = task.is_completed;
                    task.is_completed = !task.is_completed;
                    try {
                        await fetch(`/api/tasks/${task.id}`, {
                            method: 'PUT',
                            headers: { 
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ is_completed: task.is_completed })
                        });
                    } catch (error) {
                        task.is_completed = originalStatus;
                        alert('Échec de la mise à jour de la tâche');
                    }
                },
                async deleteTask(id) {
                    const originalTasks = [...this.tasks];
                    this.tasks = this.tasks.filter(t => t.id !== id);
                    try {
                        await fetch(`/api/tasks/${id}`, {
                            method: 'DELETE',
                            headers: { 
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                    } catch (error) {
                        this.tasks = originalTasks;
                        alert('Échec de la suppression de la tâche');
                    }
                }
           }" 
           x-init="fetchTasks()">
    
    <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle2 h-5 w-5 text-primary"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
        Mes Sujets & Tâches
    </h3>

    <form @submit.prevent="addTask" class="flex gap-2 mb-4">
        <x-ui.input
            placeholder="Ajouter un nouveau sujet ou une tâche..."
            x-model="newTaskTitle"
            class="flex-1"
        />
        <x-ui.button type="submit" size="icon" x-bind:disabled="!newTaskTitle.trim()" class="shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        </x-ui.button>
    </form>

    <div class="flex-1 overflow-y-auto pr-2 space-y-2 min-h-[200px]">
        <template x-if="loading">
            <p class="text-sm text-muted-foreground text-center py-4">Chargement des tâches...</p>
        </template>
        
        <template x-if="!loading && tasks.length === 0">
            <p class="text-sm text-muted-foreground text-center py-4">Aucune tâche pour le moment. Ajoutez-en une ci-dessus !</p>
        </template>

        <template x-for="task in tasks" :key="task.id">
            <div
                class="flex items-center gap-3 p-3 rounded-md border transition-colors"
                :class="task.is_completed ? 'bg-muted/50 border-transparent' : 'bg-card border-border'"
            >
                <button @click="toggleTask(task)" class="shrink-0 focus:outline-none">
                    <template x-if="task.is_completed">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle2 h-5 w-5 text-primary"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                    </template>
                    <template x-if="!task.is_completed">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle h-5 w-5 text-muted-foreground"><circle cx="12" cy="12" r="10"/></svg>
                    </template>
                </button>
                <span class="flex-1 text-sm" :class="task.is_completed ? 'line-through text-muted-foreground' : ''" x-text="task.title"></span>
                <x-ui.button
                    variant="ghost"
                    size="icon"
                    class="h-8 w-8 text-muted-foreground hover:text-destructive shrink-0"
                    @click="deleteTask(task.id)"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 h-4 w-4"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                </x-ui.button>
            </div>
        </template>
    </div>
</x-ui.card>
