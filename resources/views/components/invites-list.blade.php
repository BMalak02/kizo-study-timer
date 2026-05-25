<div x-data="{ 
    invites: [],
    async fetchInvites() {
        try {
            const res = await fetch('/api/invites', {
                headers: { 'Accept': 'application/json' }
            });
            this.invites = await res.json();
        } catch (error) {
            console.error('Failed to fetch invites', error);
        }
    },
    async handleAccept(id, challengeId) {
        try {
            await fetch(`/api/invites/${id}/accept`, { 
                method: 'POST',
                headers: { 
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                }
            });
            this.invites = this.invites.filter(inv => inv.id !== id);
            window.location.href = `/timer?challengeId=${challengeId}`;
        } catch (error) {
            alert('Échec de l\'acceptation');
        }
    },
    async handleDecline(id) {
        try {
            await fetch(`/api/invites/${id}/decline`, { 
                method: 'POST',
                headers: { 
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                }
            });
            this.invites = this.invites.filter(inv => inv.id !== id);
        } catch (error) {
            alert('Échec du refus');
        }
    }
}" x-init="fetchInvites()">
    <template x-if="invites.length > 0">
        <div class="mb-8">
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail h-5 w-5 text-primary"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                Nouvelles invitations aux défis (<span x-text="invites.length"></span>)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <template x-for="invite in invites" :key="invite.id">
                    <x-ui.card class="p-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-2 border-primary/20 bg-primary/5">
                        <div>
                            <p class="text-sm font-semibold">
                                <span x-text="invite.inviter.name"></span> vous a invité à rejoindre :
                            </p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-2xl" x-text="invite.challenge.icon"></span>
                                <span class="font-bold text-lg" x-text="invite.challenge.name"></span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <x-ui.button @click="handleAccept(invite.id, invite.challenge.id)" size="sm" class="bg-primary text-primary-foreground">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check h-4 w-4 mr-1"><path d="M20 6 9 17l-5-5"/></svg> Accepter
                            </x-ui.button>
                            <x-ui.button @click="handleDecline(invite.id)" size="sm" variant="outline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4 mr-1"><path d="M18 6 6 18"/><path d="M6 6l12 12"/></svg> Refuser
                            </x-ui.button>
                        </div>
                    </x-ui.card>
                </template>
            </div>
        </div>
    </template>
</div>
