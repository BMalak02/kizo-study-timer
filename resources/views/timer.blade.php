<x-layouts.app>
    <x-navigation />

    <script>
        function getTimerData() {
            return {
                subject: '',
                category: '',
                notes: '',
                isSaving: false,
                async handleSessionComplete(duration, xpGained) {
                    if (!duration) {
                        alert('Erreur : La durée est invalide (0 minute)');
                        return;
                    }
                    this.isSaving = true;
                    try {
                        const response = await fetch('/api/sessions', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content
                            },
                            body: JSON.stringify({
                                subject: this.subject || null,
                                category: this.category || null,
                                duration_minutes: duration,
                                session_type: 'pomodoro',
                                notes: this.notes || null,
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.subject = '';
                            this.category = '';
                            this.notes = '';
                            // Dispatch the success event to trigger the celebration overlay!
                            window.dispatchEvent(new CustomEvent('session-saved', { 
                                detail: { duration: duration, xpGained: data.xp_earned }
                            }));
                        } else {
                            const data = await response.json();
                            this.subject = '';
                            this.category = '';
                            this.notes = '';
                            alert(`Statut de l'erreur : ${response.status}. Détails : ${data.message || 'Échec de la sauvegarde'}`);
                            throw new Error('Failed to save');
                        }
                    } catch (error) {
                        if (error && error.message && !error.message.includes('Failed to save')) {
                            alert('Erreur : Échec du réseau ou blocage du navigateur.');
                            console.error(error);
                        }
                    } finally {
                        this.isSaving = false;
                    }
                }
            };
        }
    </script>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="getTimerData()" x-on:session-complete.window="handleSessionComplete($event.detail.duration, $event.detail.xpGained)">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary mb-2">
                Minuteur d'étude
            </h1>
            <p class="text-muted-foreground">
                Maîtrisez votre apprentissage avec des sessions Pomodoro focalisées
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Timer -->
            <div class="lg:col-span-1">
                <x-pomodoro-timer />
            </div>

            <!-- Session Details -->
            <div class="lg:col-span-2 space-y-6">
                <x-ui.card class="p-6 border-2 border-primary/20">
                    <h2 class="text-xl font-bold mb-4">Détails de la session</h2>

                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Sujet</label>
                            <select x-model="subject" class="w-full h-9 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                                <option value="">Sélectionnez un sujet...</option>
                                @foreach (['Mathématiques', 'Sciences', 'Anglais', 'Histoire', 'Physique', 'Chimie', 'Biologie', 'Autre'] as $subj)
                                    <option value="{{ $subj }}">{{ $subj }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium">Catégorie</label>
                            <select x-model="category" class="w-full h-9 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                                <option value="">Sélectionnez une catégorie...</option>
                                @foreach (['Étude', 'Révision', 'Pratique', 'Devoirs', 'Préparation Examen'] as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium">Notes</label>
                            <textarea
                                x-model="notes"
                                placeholder="Ajoutez des notes sur votre session d'étude..."
                                class="w-full p-3 rounded-md border border-border bg-background text-foreground placeholder:text-muted-foreground text-sm resize-none h-24 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                            ></textarea>
                        </div>
                    </div>
                </x-ui.card>

                <!-- Tips -->
                <x-ui.card class="p-6 border-2 border-secondary/20 bg-gradient-to-br from-secondary/5 to-secondary/10">
                    <h3 class="font-bold mb-4 text-secondary">Conseils d'étude</h3>
                    <ul class="space-y-2 text-sm text-foreground">
                        <li>✓ Éliminez toutes les distractions avant de commencer</li>
                        <li>✓ Faites des pauses entre les sessions</li>
                        <li>✓ Restez hydraté pendant que vous étudiez</li>
                        <li>✓ Révisez vos notes pendant les pauses</li>
                        <li>✓ Suivez vos progrès avec les sessions</li>
                    </ul>
                </x-ui.card>
            </div>
        </div>
    </main>
</x-layouts.app>
