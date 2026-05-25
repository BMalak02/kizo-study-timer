@props([])

<div x-data="celebrationOverlay()"
     x-on:session-saved.window="handleSessionSaved($event.detail)"
     x-on:challenge-completed.window="handleChallengeCompleted($event.detail)"
     class="relative z-[100]">
     
    <!-- Audio Elements -->
    <!-- Positive ringing sound -->
    <audio id="success-sound" preload="auto">
        <source src="data:audio/mp3;base64,SUQzBAAAAAAAI1RTU0UAAAAPAAADTGF2ZjYwLjE2LjEwMAAAAAAAAAAAAAAA//tQwAAAAANIAAAAAExBTUUzLjEwMKqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq//tQwAgAAANIAAAAAExBTUUzLjEwMKqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq//tQwBAAAANIAAAAAExBTUUzLjEwMKqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq//tQwCQAAANIAAAAAExBTUUzLjEwMKqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq" type="audio/mp3">
    </audio>

    <!-- Overlay UI -->
    <div x-show="show" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90"
         class="fixed inset-0 flex items-center justify-center pointer-events-none bg-background/80 backdrop-blur-sm z-[100]">
         
        <div class="text-center p-8 bg-card border-4 border-primary rounded-3xl shadow-2xl max-w-lg mx-auto pointer-events-auto relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20 -z-10"></div>
            
            <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary mb-4 drop-shadow-sm"
                x-text="title"></h2>
                
            <p class="text-xl text-foreground font-medium mb-6" x-html="message"></p>
            
            <div class="flex justify-center mb-6" x-show="xpGained > 0">
                <span class="inline-flex items-center justify-center px-4 py-2 bg-accent/20 text-accent font-bold rounded-full text-lg shadow-sm border border-accent/30">
                    +<span x-text="xpGained"></span> XP
                </span>
            </div>

            <x-ui.button @click="close()" class="w-full text-lg font-bold bg-primary hover:bg-primary/90 text-primary-foreground shadow-lg px-8 py-6 rounded-2xl">
                Continuer
            </x-ui.button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('celebrationOverlay', () => ({
            show: false,
            title: '',
            message: '',
            xpGained: 0,
            
            playBell() {
                // Play a generic beep if audio fails or data-uri is malformed
                const audio = document.getElementById('success-sound');
                if(audio) {
                    audio.play().catch(e => {
                        // Fallback browser beep using AudioContext
                        try {
                            const ctx = new (window.AudioContext || window.webkitAudioContext)();
                            const osc = ctx.createOscillator();
                            osc.type = 'sine';
                            osc.frequency.setValueAtTime(880, ctx.currentTime); // A5
                            osc.frequency.exponentialRampToValueAtTime(1760, ctx.currentTime + 0.1); 
                            osc.connect(ctx.destination);
                            osc.start();
                            osc.stop(ctx.currentTime + 0.2);
                        } catch(err) {} 
                    });
                }
            },
            
            fireConfetti() {
                if (typeof confetti !== 'undefined') {
                    var duration = 5 * 1000;
                    var animationEnd = Date.now() + duration;
                    var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 110 };

                    function randomInRange(min, max) {
                      return Math.random() * (max - min) + min;
                    }

                    var interval = setInterval(function() {
                      var timeLeft = animationEnd - Date.now();

                      if (timeLeft <= 0) {
                        return clearInterval(interval);
                      }

                      var particleCount = 50 * (timeLeft / duration);
                      
                      // balloons / classic colors
                      confetti(Object.assign({}, defaults, { particleCount,
                        origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 },
                        colors: ['#ff478f', '#ff7aa5', '#ffe4f0', '#ffff00']
                      }));
                      confetti(Object.assign({}, defaults, { particleCount,
                        origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 },
                        colors: ['#ff478f', '#ff7aa5', '#ffe4f0', '#ffff00']
                      }));
                    }, 250);
                }
            },

            handleSessionSaved(data) {
                this.title = 'Session Terminée !';
                this.message = `Super travail ! Vous avez étudié pour <strong>${data.duration} min</strong>.`;
                this.xpGained = data.xpGained || 0;
                this.playBell();
                this.fireConfetti();
                this.show = true;
                
                // Auto close after 15 seconds
                setTimeout(() => { if (this.show) this.close(); }, 15000);
            },

            handleChallengeCompleted(data) {
                this.title = '🎉 Félicitations ! 🎉';
                this.message = `Vous avez réussi le défi : <strong>${data.challengeName}</strong> !`;
                this.xpGained = data.xpGained || 0;
                this.playBell();
                this.fireConfetti();
                this.show = true;
            },

            close() {
                this.show = false;
                // Important to refresh the page after 400ms to show updated stats if needed
                setTimeout(() => { window.location.reload(); }, 400);
            }
        }));
    });
</script>
