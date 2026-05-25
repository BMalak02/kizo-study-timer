<x-layouts.app>
    <x-navigation />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ 
        challenges: [],
        userChallenges: [],
        loading: true,
        joiningChallengeId: null,
        
        async fetchChallenges() {
            this.loading = true;
            try {
                const headers = { 'Accept': 'application/json' };
                const [allRes, userRes] = await Promise.all([
                    fetch('/api/challenges', { headers }).then(res => res.json()),
                    fetch('/api/user/challenges', { headers }).then(res => res.json())
                ]);
                this.challenges = allRes;
                this.userChallenges = userRes;
            } catch (error) {
                console.error('Failed to load challenges', error);
            } finally {
                this.loading = false;
            }
        },
        
        async handleJoinChallenge(id) {
            this.joiningChallengeId = id;
            try {
                const response = await fetch(`/api/challenges/${id}/join`, {
                    method: 'POST',
                    headers: { 
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    }
                });
                if (response.ok) {
                    alert('Succès ! Vous avez rejoint ce défi.');
                    await this.fetchChallenges();
                } else {
                    alert('Erreur : Échec de l\'inscription au défi');
                }
            } catch (error) {
                alert('Erreur : Échec de l\'inscription au défi');
            } finally {
                this.joiningChallengeId = null;
            }
        },

        get difficultyColors() {
            return {
                easy: 'bg-tertiary/20 text-tertiary border-tertiary/30',
                medium: 'bg-secondary/20 text-secondary border-secondary/30',
                hard: 'bg-destructive/20 text-destructive border-destructive/30'
            };
        },

        get difficultyIcons() {
            return {
                easy: '⭐',
                medium: '⭐⭐',
                hard: '⭐⭐⭐'
            };
        },

        isUserChallenge(id) {
            return this.userChallenges.some(c => c.id === id);
        },

        simulateCompletion(challenge) {
            // Trigger the global challenge completion overlay!
            window.dispatchEvent(new CustomEvent('challenge-completed', {
                detail: { challengeName: challenge.name, xpGained: challenge.xp_reward }
            }));
            
            // Mark as complete in UI temporarily
            challenge.completed = true;
            challenge.progress = challenge.target_sessions;
        }
    }" x-init="fetchChallenges()">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary mb-2">
                Défis
            </h1>
            <p class="text-muted-foreground">
                Complétez des défis pour gagner de l'XP supplémentaire et prouver votre dévouement
            </p>
        </div>

        <x-invites-list x-on:accepted="fetchChallenges()" />

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
                <!-- Active Challenges -->
                <div class="mb-12" x-show="userChallenges.length > 0">
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy h-6 w-6 text-primary"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        Vos Défis Actifs
                    </h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <template x-for="challenge in userChallenges" :key="challenge.id">
                            <x-ui.card class="p-6 border-4 border-transparent bg-clip-border bg-gradient-to-br from-card to-card rounded-[2rem] shadow-xl relative overflow-hidden" 
                                       style="background-image: linear-gradient(white, white), linear-gradient(135deg, var(--color-primary), var(--color-secondary)); background-origin: border-box; background-clip: padding-box, border-box;">
                                
                                <div class="absolute top-0 right-0 py-1 px-4 bg-gradient-to-r from-accent to-accent/80 text-accent-foreground text-xs font-bold rounded-bl-xl shadow-sm">
                                    En cours
                                </div>

                                <div class="flex justify-between items-start mb-6 mt-2">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-extrabold text-foreground mb-1" x-text="challenge.name"></h3>
                                        <p class="text-sm text-foreground/70 font-medium" x-text="challenge.description"></p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <!-- Friends Competition List (Mocked) -->
                                    <div class="bg-background rounded-2xl p-5 shadow-inner border border-border/50">
                                        <h4 class="text-sm font-bold mb-4 text-center">Objectif : Étudier <span x-text="challenge.target_sessions"></span> sessions</h4>
                                        
                                        <!-- Friend 1 -->
                                        <div class="mb-4">
                                            <div class="flex justify-between text-xs mb-1 font-bold">
                                                <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-blue-500"></div> Bestie1</div>
                                                <span class="text-foreground/80"><span x-text="Math.floor(challenge.target_sessions * 0.9)"></span> / <span x-text="challenge.target_sessions"></span></span>
                                            </div>
                                            <div class="bg-background border border-border h-2.5 w-full overflow-hidden rounded-full">
                                                <div class="bg-blue-500 h-full rounded-full" style="width: 90%;"></div>
                                            </div>
                                        </div>

                                        <!-- Current User -->
                                        <div class="mb-4">
                                            <div class="flex justify-between text-xs mb-1 font-bold">
                                                <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-accent"></div> Vous</div>
                                                <span class="text-foreground/80"><span x-text="challenge.progress || 0"></span> / <span x-text="challenge.target_sessions"></span></span>
                                            </div>
                                            <div class="bg-background border border-border h-2.5 w-full overflow-hidden rounded-full">
                                                <div class="bg-accent h-full transition-all rounded-full"
                                                     x-bind:style="'width: ' + (((challenge.progress || 0) / challenge.target_sessions) * 100) + '%'"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Friend 2 -->
                                        <div>
                                            <div class="flex justify-between text-xs mb-1 font-bold">
                                                <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-tertiary"></div> Bestie2</div>
                                                <span class="text-foreground/80"><span x-text="Math.floor(challenge.target_sessions * 0.4)"></span> / <span x-text="challenge.target_sessions"></span></span>
                                            </div>
                                            <div class="bg-background border border-border h-2.5 w-full overflow-hidden rounded-full">
                                                <div class="bg-tertiary h-full rounded-full" style="width: 40%;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center pt-2">
                                        <div class="flex items-center justify-center bg-accent/10 px-4 py-2 rounded-full gap-2 text-sm font-extrabold text-accent">
                                            🏆 <span x-text="challenge.xp_reward"></span> XP
                                        </div>
                                        
                                        <div class="flex gap-2">
                                            <x-invite-friend-modal />
                                            <template x-if="!challenge.completed">
                                                <x-ui.button @click="simulateCompletion(challenge)" class="bg-primary hover:bg-primary/90 text-primary-foreground font-bold rounded-full px-6 shadow-md transition-transform active:scale-95">
                                                    Terminer
                                                </x-ui.button>
                                            </template>
                                            <template x-if="challenge.completed">
                                                <x-ui.badge variant="default" class="bg-tertiary text-tertiary-foreground px-4 py-2 rounded-full font-bold">
                                                    ✓ Réussi
                                                </x-ui.badge>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </x-ui.card>
                        </template>
                    </div>
                </div>

                <!-- Available Challenges -->
                <div>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target h-6 w-6 text-secondary"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                        Défis Disponibles
                    </h2>

                    <template x-if="challenges.filter(c => !isUserChallenge(c.id)).length === 0">
                        <x-ui.card class="p-12 text-center border-2 border-secondary/20">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy h-12 w-12 mx-auto mb-4 text-muted-foreground"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                            <p class="text-lg font-semibold mb-2">Vous êtes à jour !</p>
                            <p class="text-muted-foreground">
                                Vous avez rejoint tous les défis disponibles. Continuez à étudier pour les terminer.
                            </p>
                        </x-ui.card>
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-show="challenges.filter(c => !isUserChallenge(c.id)).length > 0">
                        <template x-for="challenge in challenges.filter(c => !isUserChallenge(c.id))" :key="challenge.id">
                            <x-ui.card class="p-6 border-2 border-secondary/20 hover:border-secondary/40 transition-colors">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-foreground mb-2" x-text="challenge.name"></h3>
                                        <p class="text-sm text-muted-foreground line-clamp-2 mb-3" x-text="challenge.description"></p>
                                    </div>
                                    <x-ui.badge variant="outline" x-bind:class="difficultyColors[challenge.difficulty]">
                                        <span x-text="difficultyIcons[challenge.difficulty]"></span>
                                    </x-ui.badge>
                                </div>

                                <div class="space-y-4">
                                    <div class="space-y-2">
                                        <div class="text-sm">
                                            <p class="font-medium">Objectif : <span x-text="challenge.target_sessions"></span> sessions</p>
                                            <template x-if="challenge.target_duration">
                                                <p class="text-xs text-muted-foreground">ou <span x-text="challenge.target_duration"></span> minutes</p>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center pt-2 border-t border-border">
                                        <div class="flex items-center gap-1 text-sm font-semibold text-accent">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap h-4 w-4 fill-accent"><path d="M4 14.75 15.3 3 13.5 9.25h6.7L8.7 21l1.8-6.25H4Z"/></svg>
                                            <span x-text="challenge.xp_reward"></span> XP
                                        </div>
                                        <x-ui.button @click="handleJoinChallenge(challenge.id)" 
                                                     x-bind:disabled="joiningChallengeId === challenge.id"
                                                     class="bg-gradient-to-r from-secondary to-secondary/80 hover:from-secondary/90 hover:to-secondary/70 text-secondary-foreground" 
                                                     size="sm">
                                            <template x-if="joiningChallengeId === challenge.id">
                                                <svg class="animate-spin h-3 w-3 mr-1 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </template>
                                            <span x-text="joiningChallengeId === challenge.id ? 'Inscription...' : 'Rejoindre le défi'"></span>
                                        </x-ui.button>
                                        <x-invite-friend-modal />
                                    </div>
                                </div>
                            </x-ui.card>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </main>
</x-layouts.app>
