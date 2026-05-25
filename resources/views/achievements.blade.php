<x-layouts.app>
    <x-navigation />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
        achievements: [],
        userAchievements: [],
        loading: true,
        
        async fetchAchievements() {
            try {
                const [allRes, userRes] = await Promise.all([
                    fetch('/api/achievements').then(res => res.json()),
                    fetch('/api/user/achievements').then(res => res.json())
                ]);
                this.achievements = allRes;
                this.userAchievements = userRes;
            } catch (error) {
                console.error('Failed to load achievements', error);
            } finally {
                this.loading = false;
            }
        },

        isUnlocked(id) {
            return this.userAchievements.some(a => a.id === id);
        },

        getUnlockedDate(id) {
            const ua = this.userAchievements.find(a => a.id === id);
            return ua ? new Date(ua.unlocked_at).toLocaleDateString() : null;
        },

        get unlockedCount() { return this.userAchievements.length; },
        get unlockedPercentage() { 
            return this.achievements.length > 0 
                ? Math.round((this.unlockedCount / this.achievements.length) * 100) 
                : 0; 
        },

        get categoryColors() {
            return {
                sessions: 'bg-primary/20 text-primary border-primary/30',
                xp: 'bg-accent/20 text-accent border-accent/30',
                streak: 'bg-destructive/20 text-destructive border-destructive/30',
                challenges: 'bg-secondary/20 text-secondary border-secondary/30',
                special: 'bg-tertiary/20 text-tertiary border-tertiary/30'
            };
        },

        get categoryLabels() {
            return {
                sessions: 'Sessions d\'étude',
                xp: 'Points d\'Expérience',
                streak: 'Série',
                challenges: 'Défis',
                special: 'Spécial'
            };
        },

        get groupedAchievements() {
            return this.achievements.reduce((acc, achievement) => {
                const cat = achievement.category;
                if (!acc[cat]) acc[cat] = [];
                acc[cat].push(achievement);
                return acc;
            }, {});
        }
    }" x-init="fetchAchievements()">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary mb-2">
                Succès
            </h1>
            <p class="text-muted-foreground">
                Débloquez des badges et prouvez votre dévouement à l'apprentissage
            </p>
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
                <!-- Progress Summary -->
                <x-ui.card class="mb-8 p-8 border-2 border-primary/20 bg-gradient-to-r from-primary/10 via-accent/5 to-secondary/10">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground mb-2">Progression des succès</p>
                            <div class="flex items-baseline gap-2">
                                <p class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary" x-text="unlockedCount"></p>
                                <p class="text-lg text-muted-foreground">/ <span x-text="achievements.length"></span></p>
                            </div>
                        </div>

                        <div class="flex-1 sm:flex-initial">
                            <div class="w-full sm:w-48">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="font-medium">Accomplissement</span>
                                    <span class="text-accent font-semibold" x-text="unlockedPercentage + '%'"></span>
                                </div>
                                <div class="w-full h-4 bg-muted rounded-full overflow-hidden">
                                    <div
                                        class="h-full bg-gradient-to-r from-primary to-accent transition-all duration-300"
                                        :style="'width: ' + unlockedPercentage + '%'"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-ui.card>

                <!-- Achievements by Category -->
                <div class="space-y-10">
                    <template x-for="[category, categoryAchievements] in Object.entries(groupedAchievements)" :key="category">
                        <div>
                            <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award h-6 w-6"><path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"/><circle cx="12" cy="8" r="6"/></svg>
                                <span x-text="categoryLabels[category]"></span>
                            </h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <template x-for="achievement in categoryAchievements" :key="achievement.id">
                                    <x-ui.card
                                        class="p-6 border-2 transition-all"
                                        x-bind:class="isUnlocked(achievement.id)
                                            ? `border-primary/50 bg-gradient-to-br from-primary/10 to-primary/5 hover:border-primary`
                                            : 'border-muted/30 bg-muted/30 opacity-60'"
                                    >
                                        <div class="text-center">
                                            <div class="relative inline-block mb-3">
                                                <div class="text-4xl" x-text="achievement.icon"></div>
                                                <template x-if="!isUnlocked(achievement.id)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock absolute bottom-0 right-0 h-5 w-5 text-destructive bg-background rounded-full p-0.5"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                                </template>
                                            </div>

                                            <h3 class="font-bold text-foreground mb-1" x-text="achievement.name"></h3>
                                            <p class="text-xs text-muted-foreground line-clamp-2 mb-3" x-text="achievement.description"></p>

                                            <x-ui.badge variant="outline" x-bind:class="categoryColors[category]">
                                                <span x-text="categoryLabels[category]"></span>
                                            </x-ui.badge>

                                            <template x-if="isUnlocked(achievement.id) && getUnlockedDate(achievement.id)">
                                                <p class="text-xs text-muted-foreground mt-3">
                                                    Débloqué le <span x-text="getUnlockedDate(achievement.id)"></span>
                                                </p>
                                            </template>
                                        </div>
                                    </x-ui.card>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </main>
</x-layouts.app>
