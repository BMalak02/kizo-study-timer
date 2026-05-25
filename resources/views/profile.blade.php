<x-layouts.app>
    <x-navigation />

    <script>
        function getProfileData() {
            return {
                user: @js(auth()->user()),
                name: '{{ auth()->user()->name }}',
                email: '{{ auth()->user()->email }}',
                isEditing: false,
                isSaving: false,
                stats: {
                    total_study_minutes: 0,
                    completed_sessions: 0,
                    current_streak: 0,
                    xp: 0
                },
                recentSessions: [],
                loading: true,

                async init() {
                    try {
                        const headers = { 'Accept': 'application/json' };
                        const [statsRes, sessionsRes] = await Promise.all([
                            fetch('/api/sessions/stats?days=30', { headers }).then(res => res.json()),
                            fetch('/api/sessions', { headers }).then(res => res.json())
                        ]);
                        this.stats = statsRes;
                        this.recentSessions = sessionsRes.data || sessionsRes;
                    } catch (error) {
                        console.error('Failed to load profile data', error);
                    } finally {
                        this.loading = false;
                    }
                },

                async handleSaveProfile() {
                    this.isSaving = true;
                    try {
                        const response = await fetch('/api/user', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                            },
                            body: JSON.stringify({ name: this.name, email: this.email })
                        });
                        if (response.ok) {
                            alert('Profil mis à jour avec succès');
                            this.isEditing = false;
                            window.location.reload();
                        } else {
                            alert('Échec de la mise à jour du profil');
                        }
                    } catch (error) {
                        alert('Erreur lors de la mise à jour du profil');
                    } finally {
                        this.isSaving = false;
                    }
                }
            };
        }
    </script>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="getProfileData()">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary mb-2">
                Mon Profil
            </h1>
            <p class="text-muted-foreground">Gérez votre compte et visualisez vos progrès</p>
        </div>

        <!-- Loading State -->
        <template x-if="loading">
            <div class="flex items-center justify-center h-96">
                <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </template>

        <template x-if="!loading">
            <div>
                <!-- Profile Card & Stats -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <x-ui.card class="lg:col-span-1 p-8 border-2 border-primary/20 bg-gradient-to-br from-primary/10 to-primary/5">
                        <div class="text-center">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary to-secondary mx-auto mb-4 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-10 w-10 text-primary-foreground"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>

                            <div x-show="!isEditing">
                                <h2 class="text-2xl font-bold text-foreground mb-1" x-text="user.name"></h2>
                                <p class="text-sm text-muted-foreground mb-2" x-text="user.email"></p>
                                <div class="mb-6 pb-6 border-b border-border">
                                    <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">
                                        Niveau <span x-text="user.level || 1"></span>
                                    </p>
                                </div>
                                <x-ui.button @click="isEditing = true" class="w-full bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-primary-foreground">
                                    Modifier le profil
                                </x-ui.button>
                            </div>

                            <div x-show="isEditing" class="space-y-4">
                                <div class="space-y-2 text-left">
                                    <label class="text-sm font-medium">Nom</label>
                                    <x-ui.input x-model="name" class="bg-background border-border" />
                                </div>
                                <div class="space-y-2 text-left">
                                    <label class="text-sm font-medium">E-mail</label>
                                    <x-ui.input type="email" x-model="email" class="bg-background border-border" />
                                </div>
                                <div class="flex gap-2">
                                    <x-ui.button @click="handleSaveProfile" x-bind:disabled="isSaving" class="flex-1 bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-primary-foreground">
                                        <span x-text="isSaving ? 'Enregistrement...' : 'Enregistrer'"></span>
                                    </x-ui.button>
                                    <x-ui.button @click="isEditing = false" variant="outline" class="flex-1">
                                        Annuler
                                    </x-ui.button>
                                </div>
                            </div>
                        </div>
                    </x-ui.card>

                    <!-- Quick Stats -->
                    <div class="lg:col-span-2 grid grid-cols-2 gap-4">
                        <x-stats-card title="Minutes Totales" x-text="stats.total_study_minutes" value="0" gradient="primary">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock h-8 w-8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </x-slot>
                        </x-stats-card>
                        <x-stats-card title="Sessions" x-text="stats.completed_sessions" value="0" gradient="secondary">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target h-8 w-8"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                            </x-slot>
                        </x-stats-card>
                        <x-stats-card title="Série Actuelle" x-text="stats.current_streak" value="0" gradient="accent">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame h-8 w-8"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.21 1.146-3.027a1.722 1.722 0 0 1 2.354 0Z"/></svg>
                            </x-slot>
                        </x-stats-card>
                        <x-stats-card title="XP Total" x-text="stats.xp" value="0" gradient="tertiary">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award h-8 w-8"><path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"/><circle cx="12" cy="8" r="6"/></svg>
                            </x-slot>
                        </x-stats-card>
                    </div>
                </div>

                <!-- Recent Sessions -->
                <x-ui.card class="p-6 border-2 border-secondary/20">
                    <h2 class="text-xl font-bold mb-4">Sessions Récentes</h2>

                    <template x-if="recentSessions.length === 0">
                        <p class="text-muted-foreground text-center py-8">
                            Aucune session pour le moment. Commencez à étudier pour voir vos progrès ici !
                        </p>
                    </template>

                    <template x-if="recentSessions.length > 0">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="border-b border-border">
                                    <tr class="text-muted-foreground">
                                        <th class="text-left py-3 px-4 font-semibold">Sujet</th>
                                        <th class="text-left py-3 px-4 font-semibold">Catégorie</th>
                                        <th class="text-right py-3 px-4 font-semibold">Durée</th>
                                        <th class="text-right py-3 px-4 font-semibold">XP Gagnés</th>
                                        <th class="text-right py-3 px-4 font-semibold">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="session in recentSessions.slice(0, 5)" :key="session.id">
                                        <tr class="border-b border-border/50 hover:bg-muted/30 transition-colors">
                                            <td class="py-3 px-4 font-medium" x-text="session.subject || '-'"></td>
                                            <td class="py-3 px-4" x-text="session.category || '-'"></td>
                                            <td class="py-3 px-4 text-right" x-text="session.duration_minutes + 'm'"></td>
                                            <td class="py-3 px-4 text-right font-semibold text-accent" x-text="session.xp_earned"></td>
                                            <td class="py-3 px-4 text-right text-muted-foreground" x-text="new Date(session.completed_at).toLocaleDateString()"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </template>
                </x-ui.card>
            </div>
        </template>
    </main>
</x-layouts.app>
