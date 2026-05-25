@props(['onSessionComplete' => ''])

<x-ui.card class="w-full max-w-md mx-auto p-8 border-2 border-primary/20 bg-gradient-to-br from-card to-card/80"
           x-data="{
                workMinutes: 25,
                breakMinutes: 5,
                totalSeconds: 25 * 60,
                remainingSeconds: 25 * 60,
                isRunning: false,
                isWorkSession: true,
                sessionCount: 0,
                soundEnabled: true,
                interval: null,

                init() {
                    this.loadState();
                    if (this.isRunning) {
                        this.startInterval();
                    }
                    window.addEventListener('beforeunload', () => {
                        if (this.isRunning) {
                            this.saveState();
                        }
                    });
                },

                saveState() {
                    localStorage.setItem('pomodoro_state', JSON.stringify({
                        workMinutes: this.workMinutes,
                        breakMinutes: this.breakMinutes,
                        totalSeconds: this.totalSeconds,
                        remainingSeconds: this.remainingSeconds,
                        isRunning: this.isRunning,
                        isWorkSession: this.isWorkSession,
                        sessionCount: this.sessionCount,
                        endTime: this.isRunning ? Date.now() + (this.remainingSeconds * 1000) : null
                    }));
                },

                loadState() {
                    const saved = localStorage.getItem('pomodoro_state');
                    if (saved) {
                        try {
                            const state = JSON.parse(saved);
                            this.workMinutes = state.workMinutes || 25;
                            this.breakMinutes = state.breakMinutes || 5;
                            this.totalSeconds = state.totalSeconds || (25 * 60);
                            this.isWorkSession = state.isWorkSession !== undefined ? state.isWorkSession : true;
                            this.sessionCount = state.sessionCount || 0;
                            
                            if (state.isRunning && state.endTime) {
                                const now = Date.now();
                                if (state.endTime > now) {
                                    this.remainingSeconds = Math.round((state.endTime - now) / 1000);
                                    this.isRunning = true;
                                } else {
                                    this.remainingSeconds = 0;
                                    this.isRunning = false;
                                    setTimeout(() => this.timerCompleted(), 100);
                                }
                            } else {
                                this.remainingSeconds = state.remainingSeconds !== undefined ? state.remainingSeconds : this.totalSeconds;
                                this.isRunning = false;
                            }
                        } catch (e) {
                            console.error('Failed to load pomodoro state');
                        }
                    }
                },

                get minutes() { return Math.floor(this.remainingSeconds / 60) },
                get seconds() { return this.remainingSeconds % 60 },
                get progress() { return ((this.totalSeconds - this.remainingSeconds) / this.totalSeconds) * 100 },
                get xpGained() { return Math.floor((this.totalSeconds - this.remainingSeconds) / 60) * 2 },

                formatTime(num) { return String(num).padStart(2, '0') },

                startTimer() {
                    if (this.isRunning) return;
                    this.isRunning = true;
                    this.saveState();
                    this.startInterval();
                },

                startInterval() {
                    this.interval = setInterval(() => {
                        if (this.remainingSeconds > 0) {
                            this.remainingSeconds--;
                            if (this.remainingSeconds % 5 === 0) this.saveState(); // Save every 5 seconds just in case
                        } else {
                            this.timerCompleted();
                        }
                    }, 1000);
                },

                pauseTimer() {
                    this.isRunning = false;
                    clearInterval(this.interval);
                    this.saveState();
                },

                resetTimer() {
                    this.pauseTimer();
                    this.isWorkSession = true;
                    this.sessionCount = 0;
                    this.updateTotalSeconds();
                    this.saveState();
                },

                updateTotalSeconds() {
                    const mins = this.isWorkSession ? this.workMinutes : this.breakMinutes;
                    this.totalSeconds = mins * 60;
                    this.remainingSeconds = this.totalSeconds;
                },

                timerCompleted() {
                    this.playSound();
                    if (this.isWorkSession) {
                        this.sessionCount++;
                        this.isWorkSession = false;
                    } else {
                        this.isWorkSession = true;
                    }
                    this.updateTotalSeconds();
                    this.saveState();
                },

                playSound() {
                    if (!this.soundEnabled) return;
                    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();
                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);
                    oscillator.frequency.value = 800;
                    oscillator.type = 'sine';
                    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
                    oscillator.start(audioContext.currentTime);
                    oscillator.stop(audioContext.currentTime + 0.5);
                },

                async handleComplete() {
                    this.pauseTimer();
                    const minutesStudied = this.workMinutes * this.sessionCount;
                    const xpGained = this.xpGained;
                    
                    // Reset internal state and persistence after completion
                    this.sessionCount = 0;
                    this.isWorkSession = true;
                    this.updateTotalSeconds();
                    this.saveState();

                    // Dispatch event to parent instead of calling global function
                    this.$dispatch('session-complete', { duration: minutesStudied, xpGained: xpGained });
                }
           }">
    <div class="space-y-6">
        <!-- Timer Display -->
        <div class="relative w-[300px] h-[300px] mx-auto mb-8">
            <!-- Circular Progress -->
            <svg class="absolute inset-0 w-full h-full -rotate-90" style="filter: drop-shadow(0 0 15px rgba(255, 71, 143, 0.2))">
                <circle cx="150" cy="150" r="140" fill="none" stroke="currentColor" stroke-width="8" class="text-secondary/10" />
                <circle cx="150" cy="150" r="140" fill="none" stroke="currentColor" stroke-width="8" 
                        pathLength="100"
                        :stroke-dasharray="progress + ' 100'"
                        class="text-transparent transition-all duration-500"
                        :class="isWorkSession ? 'text-primary' : 'text-tertiary'" />
            </svg>

            <!-- Time Display -->
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <div class="text-[5rem] tracking-tight font-black text-primary leading-none mb-2" style="font-family: 'Inter', sans-serif;">
                    <span x-text="formatTime(minutes)"></span>:<span x-text="formatTime(seconds)"></span>
                </div>
                <div class="text-sm font-semibold text-muted-foreground mt-2" x-text="isWorkSession ? 'Session de Travail' : 'Temps de Pause'"></div>
            </div>
        </div>

        <!-- Session Info -->
        <div class="text-center">
            <p class="text-sm text-muted-foreground mb-2">
                Sessions terminées : <span class="font-semibold text-primary" x-text="sessionCount"></span>
            </p>
            <p class="text-sm text-muted-foreground">
                XP gagnés : <span class="font-semibold text-accent" x-text="xpGained"></span>
            </p>
        </div>

        <!-- Controls -->
        <div class="flex gap-3 justify-center">
            <template x-if="!isRunning">
                <x-ui.button @click="startTimer" class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-primary-foreground" size="lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-play h-5 w-5 mr-2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    Démarrer
                </x-ui.button>
            </template>
            <template x-if="isRunning">
                <x-ui.button @click="pauseTimer" variant="outline" class="border-primary/50" size="lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pause h-5 w-5 mr-2"><rect width="4" height="16" x="6" y="4"/><rect width="4" height="16" x="14" y="4"/></svg>
                    Pause
                </x-ui.button>
            </template>

            <x-ui.button @click="resetTimer" variant="outline" class="border-primary/50" size="lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw h-5 w-5"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
            </x-ui.button>

            <x-ui.button @click="soundEnabled = !soundEnabled" variant="outline" class="border-primary/50" size="lg">
                <template x-if="soundEnabled">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-volume2 h-5 w-5"><path d="M11 5 6 9H2v6h4l5 4V5z"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/></svg>
                </template>
                <template x-if="!soundEnabled">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-volume-x h-5 w-5"><path d="M11 5 6 9H2v6h4l5 4V5z"/><line x1="23" x2="17" y1="9" y2="15"/><line x1="17" x2="23" y1="9" y2="15"/></svg>
                </template>
            </x-ui.button>
        </div>

        <!-- Settings -->
        <div x-show="!isRunning" class="space-y-4 pt-4 border-t border-border">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium">Travail (min)</label>
                    <x-ui.input type="number" min="1" max="60" x-model.number="workMinutes" @input="isWorkSession && updateTotalSeconds(); saveState()" class="bg-background border-border" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Pause (min)</label>
                    <x-ui.input type="number" min="1" max="30" x-model.number="breakMinutes" @input="!isWorkSession && updateTotalSeconds(); saveState()" class="bg-background border-border" />
                </div>
            </div>
        </div>

        <!-- Complete Button -->
        <template x-if="sessionCount > 0 && !isRunning">
            <x-ui.button @click="handleComplete" class="w-full bg-gradient-to-r from-secondary to-tertiary hover:from-secondary/90 hover:to-tertiary/90 text-secondary-foreground font-semibold">
                Enregistrer & Terminer la session
            </x-ui.button>
        </template>
    </div>
</x-ui.card>
