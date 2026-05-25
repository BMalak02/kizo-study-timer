<div x-data="{ 
    open: false, 
    email: '', 
    loading: false,
    async handleInvite() {
        if (!this.email) return;
        this.loading = true;
        try {
            const response = await fetch(`/api/invites/${challenge.id}`, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: this.email })
            });

            if (response.ok) {
                alert('Succès ! Invitation envoyée à votre ami !');
                this.email = '';
                this.open = false;
            } else {
                const data = await response.json();
                alert('Erreur : ' + (data.message || 'Échec de l\'envoi de l\'invitation'));
            }
        } catch (error) {
            alert('Erreur : Échec de l\'envoi de l\'invitation');
        } finally {
            this.loading = false;
        }
    }
}">
    <!-- Trigger -->
    <x-ui.button @click="open = true" variant="outline" size="sm" class="ml-2 gap-2 text-sm border-primary/50 text-primary hover:bg-primary/10 rounded-full px-4 font-bold">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-plus w-4 h-4"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
        Inviter des amis
    </x-ui.button>

    <!-- Modal Backdrop -->
    <div x-show="open" 
         class="fixed inset-0 z-50 bg-background/80 backdrop-blur-sm flex items-center justify-center p-4"
         @click.away="open = false"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Modal Content -->
        <x-ui.card class="w-full max-w-md shadow-lg border-2 border-primary/20 relative" @click.stop="">
            <div class="p-6">
                <!-- Close Button -->
                <button @click="open = false" class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4"><path d="M18 6 6 18"/><path d="M6 6l12 12"/></svg>
                </button>

                <div class="mb-4">
                    <h3 class="text-lg font-bold">Inviter un ami</h3>
                    <p class="text-sm text-muted-foreground">
                        Envoyez une invitation par e-mail à un ami pour qu'il puisse vous rejoindre dans ce défi !
                    </p>
                </div>

                <form @submit.prevent="handleInvite" class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <x-ui.input
                            placeholder="friend@example.com"
                            type="email"
                            x-model="email"
                            required
                        />
                    </div>
                    <x-ui.button type="submit" x-bind:disabled="loading" class="w-full">
                        <template x-if="loading">
                             <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="loading ? 'Envoi...' : 'Envoyer l\'invitation'"></span>
                    </x-ui.button>
                </form>
            </div>
        </x-ui.card>
    </div>
</div>
