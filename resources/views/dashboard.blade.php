<x-layouts.app>
    <x-navigation />

    <script>
        function getDashboardData() {
            return {
                user: @js(auth()->user()),
                stats: {
                    total_minutes: 0,
                    completed_sessions: 0,
                    current_streak: 0,
                    minutes_this_period: 0,
                    sessions_this_period: 0,
                    chart_data: [],
                    active_challenges: []
                },
                leaderboard: [],
                loading: true,
                async init() {
                    try {
                        const [statsRes, leadRes] = await Promise.all([
                            fetch('/api/sessions/stats?days=7').then(res => res.json()),
                            fetch('/api/leaderboard').then(res => res.json())
                        ]);
                        this.stats = statsRes;
                        this.leaderboard = leadRes;
                    } catch (error) {
                        console.error('Failed to fetch dashboard data:', error);
                    } finally {
                        this.loading = false;
                        this.$nextTick(() => {
                            if (this.stats.chart_data?.length) {
                                this.initChart();
                            }
                        });
                    }
                },
                formatDuration(mins) {
                    if (!mins) return '0m';
                    const h = Math.floor(mins / 60);
                    const m = mins % 60;
                    return h > 0 ? `${h}h ${m}m` : `${m}m`;
                },
                initChart() {
                    const ctx = document.getElementById('activityChart')?.getContext('2d');
                    if (!ctx || !this.stats.chart_data) return;
                    
                    const labels = this.stats.chart_data.map(d => d.label);
                    const data = this.stats.chart_data.map(d => d.minutes);

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Minutes étudiées',
                                    data: data,
                                    borderColor: 'oklch(0.55 0.25 280)', // Indigo
                                    backgroundColor: 'oklch(0.55 0.25 280 / 0.1)',
                                    borderWidth: 3,
                                    pointBackgroundColor: '#ffffff',
                                    pointBorderColor: 'oklch(0.55 0.25 280)',
                                    pointBorderWidth: 2,
                                    pointRadius: 6,
                                    pointHoverRadius: 8,
                                    fill: true,
                                    tension: 0.4
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: { backgroundColor: 'oklch(0.2 0.05 260)', titleColor: 'white', padding: 12, cornerRadius: 8 }
                            },
                            scales: {
                                x: { grid: { display: false }, ticks: { color: 'oklch(0.5 0.05 240)', font: { size: 13, weight: 'bold' } } },
                                y: { beginAtZero: true, grid: { color: 'oklch(0.9 0.02 240)' }, ticks: { color: 'oklch(0.5 0.05 240)' } }
                            }
                        }
                    });
                }
            };
        }
    </script>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="getDashboardData()">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary mb-2">
                Bienvenue, {{ auth()->user()->name }} !
            </h1>
            <p class="text-muted-foreground">Continuez à avancer vers vos objectifs d'apprentissage</p>
        </div>

        <!-- Level Progress -->
        <x-ui.card class="mb-8 p-8 border-2 border-primary/20 bg-gradient-to-r from-primary/10 via-secondary/5 to-tertiary/10">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-medium text-muted-foreground mb-1">Votre Niveau</p>
                    <p class="text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">
                        {{ auth()->user()->level ?? 1 }}
                    </p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap h-12 w-12 text-accent fill-accent"><path d="M4 14.75 15.3 3 13.5 9.25h6.7L8.7 21l1.8-6.25H4Z"/></svg>
            </div>

            @php
                $xpNeeded = 1000;
                $currentXp = auth()->user()->xp ?? 0;
                $progress = ($currentXp % $xpNeeded) / $xpNeeded * 100;
            @endphp

            <div class="space-y-2">
                <div class="flex justify-between text-sm mb-2">
                    <span class="font-medium">Progression XP</span>
                    <span class="text-muted-foreground">{{ $currentXp % $xpNeeded }} / {{ $xpNeeded }}</span>
                </div>
                <div class="w-full h-3 bg-muted rounded-full overflow-hidden">
                    <div
                        class="h-full bg-gradient-to-r from-primary to-secondary transition-all duration-300"
                        style="width: {{ $progress }}%"
                    ></div>
                </div>
            </div>
        </x-ui.card>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <x-stats-card title="Minutes Totales" x-text="stats.total_minutes" value="0" subtitle="Temps d'étude total" gradient="primary">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock h-8 w-8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </x-slot>
            </x-stats-card>
            <x-stats-card title="Sessions" x-text="stats.completed_sessions" value="0" subtitle="Sessions terminées" gradient="secondary">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target h-8 w-8"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                </x-slot>
            </x-stats-card>
            <x-stats-card title="Série Actuelle" x-text="stats.current_streak" value="0" subtitle="Jours consécutifs" gradient="accent">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame h-8 w-8"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.21 1.146-3.027a1.722 1.722 0 0 1 2.354 0Z"/></svg>
                </x-slot>
            </x-stats-card>
            <x-stats-card title="Cette Semaine" x-text="stats.minutes_this_period + 'm'" value="0m" subtitle="Sessions cette période" gradient="tertiary">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up h-8 w-8"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                </x-slot>
            </x-stats-card>
            <x-stats-card title="XP" value="{{ auth()->user()->xp ?? 0 }}" subtitle="Points d'expérience totaux" gradient="primary">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap h-8 w-8"><path d="M4 14.75 15.3 3 13.5 9.25h6.7L8.7 21l1.8-6.25H4Z"/></svg>
                </x-slot>
            </x-stats-card>
        </div>
        
        <!-- Active Challenges Section -->
        <div class="mb-8" x-show="stats.active_challenges && stats.active_challenges.length > 0">
            <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy h-6 w-6 text-primary"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                Vos Défis Actifs
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="challenge in stats.active_challenges" :key="challenge.id">
                    <x-ui.card class="p-6 border-2 border-primary/20 bg-card hover:border-primary/40 transition-all shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-lg" x-text="challenge.name"></h3>
                                <p class="text-xs text-muted-foreground line-clamp-1" x-text="challenge.description"></p>
                            </div>
                            <div class="bg-accent/10 text-accent text-[10px] font-black px-2 py-1 rounded-full uppercase">
                                <span x-text="challenge.xp_reward"></span> XP
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between text-xs font-bold">
                                <span>Progression</span>
                                <span class="text-primary"><span x-text="challenge.progress || 0"></span> / <span x-text="challenge.target_sessions"></span></span>
                            </div>
                            <div class="w-full h-2 bg-muted rounded-full overflow-hidden">
                                <div class="h-full bg-primary transition-all duration-500" 
                                     :style="'width: ' + ((challenge.progress / challenge.target_sessions) * 100) + '%'"></div>
                            </div>
                        </div>
                    </x-ui.card>
                </template>
            </div>
        </div>

        <!-- Charts and Leaderboard Layout -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <!-- Weekly Chart -->
            <x-ui.card class="p-6 border-4 border-transparent bg-clip-border rounded-[2rem] shadow-xl relative overflow-hidden" 
                       style="background-image: linear-gradient(white, white), linear-gradient(135deg, var(--color-primary), var(--color-secondary)); background-origin: border-box; background-clip: padding-box, border-box;">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-extrabold text-primary mb-1">Cette Semaine</h2>
                        <div class="text-sm font-bold text-primary/70 bg-primary/10 px-3 py-1 rounded-full inline-block">
                            {{ now()->subDays(6)->format('d M') }} - {{ now()->format('d M') }}
                        </div>
                    </div>
                </div>
                <div class="h-[300px]">
                    <canvas id="activityChart"></canvas>
                </div>
            </x-ui.card>

            <!-- Leaderboard -->
            <div class="space-y-6">
                <!-- Title & Meta -->
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-foreground flex items-center justify-center gap-2 mb-2">
                        🏆 Classements
                    </h2>
                    <div class="px-6 py-2 bg-gradient-to-r from-primary to-secondary text-white font-black text-sm rounded-full inline-block mb-4 shadow-lg shadow-primary/30">
                        Top 100 Utilisateurs
                    </div>
                    
                    <div class="flex justify-between max-w-sm mx-auto p-4 bg-card rounded-[2rem] shadow-sm mb-6 border border-border">
                        <div class="text-center">
                            <div class="text-3xl font-black text-primary flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> 
                                <span x-text="leaderboard.length"></span>
                            </div>
                            <div class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Utilisateurs Totaux</div>
                        </div>
                        <div class="w-px bg-border"></div>
                        <div class="text-center">
                            <div class="text-3xl font-black text-primary flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg> 
                                #<span x-text="leaderboard.findIndex(u => u.id === user.id) + 1"></span>
                            </div>
                            <div class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Votre Position</div>
                        </div>
                    </div>
                </div>

                <!-- Leaderboard List -->
                <div class="space-y-3">
                    <template x-for="item in leaderboard" :key="item.id">
                        <div class="flex items-center justify-between p-4 rounded-[1.5rem] transition-all duration-300 group hover:scale-[1.02] border-2"
                             :class="item.id === user.id 
                                ? 'bg-primary/5 border-primary/40 shadow-sm' 
                                : 'bg-card border-border shadow-sm'">
                            <div class="flex items-center gap-4">
                                <div class="text-2xl font-black w-8 text-center flex-shrink-0" 
                                     :class="item.rank === 1 ? 'text-primary' : 'text-muted-foreground'">
                                    <span x-show="item.rank === 1">🥇</span>
                                    <span x-show="item.rank === 2">🥈</span>
                                    <span x-show="item.rank === 3">🥉</span>
                                    <span x-show="item.rank > 3" x-text="item.rank + '.'"></span>
                                </div>
                                <div class="w-12 h-12 rounded-full shadow-inner flex-shrink-0 border-2 border-white overflow-hidden bg-muted">
                                    <template x-if="item.avatar">
                                        <img :src="item.avatar" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!item.avatar">
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-primary to-secondary text-white font-bold" x-text="item.name.charAt(0)"></div>
                                    </template>
                                </div>
                                <div>
                                    <div class="font-extrabold text-lg flex items-center gap-2"
                                         :class="item.id === user.id ? 'text-primary' : 'text-foreground'">
                                        <span x-text="item.name"></span>
                                        <span x-show="item.id === user.id" class="text-[10px] bg-primary text-white px-2 py-0.5 rounded-full uppercase tracking-wider">Vous</span>
                                    </div>
                                    <div class="text-xs font-bold text-muted-foreground" x-text="'Niveau ' + item.level"></div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-black text-lg" :class="item.id === user.id ? 'text-primary' : 'text-foreground'" x-text="formatDuration(item.total_study_minutes)"></div>
                                <div class="text-sm font-bold text-accent drop-shadow-sm" x-text="item.current_streak + ' 🔥'"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </main>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</x-layouts.app>
