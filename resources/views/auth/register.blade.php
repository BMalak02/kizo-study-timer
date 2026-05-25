<x-layouts.app>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary/10 via-background to-secondary/10 p-4">
        <x-ui.card class="w-full max-w-md shadow-lg border-2 border-primary/20">
            <div class="p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary mb-2">
                        Kizo
                    </h1>
                    <p class="text-muted-foreground">Commencez votre aventure d'apprentissage</p>
                </div>

                <form @submit.prevent="async () => {
                    error = null;
                    if (password !== password_confirmation) {
                        error = 'Les mots de passe ne correspondent pas';
                        return;
                    }
                    isLoading = true;
                    try {
                        const response = await fetch('/api/register', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ name, email, password, password_confirmation })
                        });
                        if (response.ok) {
                            window.location.href = '/dashboard';
                        } else {
                            const data = await response.json();
                            error = data.message || 'Échec de l\'inscription';
                        }
                    } catch (err) {
                        error = 'Une erreur est survenue. Veuillez réessayer.';
                    } finally {
                        isLoading = false;
                    }
                }" x-data="{ name: '', email: '', password: '', password_confirmation: '', error: null, isLoading: false }" class="space-y-4">
                    @csrf
                    <template x-if="error">
                        <div class="mb-6 p-4 rounded-md bg-destructive/15 text-destructive border border-destructive/20 text-sm" x-text="error"></div>
                    </template>

                    <div class="space-y-2">
                        <label for="name" class="text-sm font-medium">
                            Nom complet
                        </label>
                        <x-ui.input
                            id="name"
                            name="name"
                            type="text"
                            placeholder="John Doe"
                            x-model="name"
                            required
                            class="bg-card border-border"
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium">
                            Email
                        </label>
                        <x-ui.input
                            id="email"
                            name="email"
                            type="email"
                            placeholder="your@email.com"
                            x-model="email"
                            required
                            class="bg-card border-border"
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="text-sm font-medium">
                            Mot de passe
                        </label>
                        <x-ui.input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="••••••••"
                            x-model="password"
                            required
                            class="bg-card border-border"
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-sm font-medium">
                            Confirmer le mot de passe
                        </label>
                        <x-ui.input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            placeholder="••••••••"
                            x-model="password_confirmation"
                            required
                            class="bg-card border-border"
                        />
                    </div>

                    <x-ui.button
                        type="submit"
                        x-bind:disabled="isLoading"
                        class="w-full bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-primary-foreground font-semibold"
                    >
                        <template x-if="isLoading">
                             <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="isLoading ? 'Création du compte...' : 'S\'inscrire'"></span>
                    </x-ui.button>
                </form>

                <div class="mt-6 text-center text-sm">
                    <p class="text-muted-foreground mb-2">
                        Vous avez déjà un compte ?
                        <a href="{{ route('login') }}" class="text-primary hover:underline font-semibold">
                            Connectez-vous ici
                        </a>
                    </p>
                </div>
            </div>
        </x-ui.card>
    </div>
</x-layouts.app>
