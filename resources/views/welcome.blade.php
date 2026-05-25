<x-layouts.app>
    <div class="min-h-screen bg-background">
        <!-- Navigation -->
        <nav class="border-b border-border bg-card/95 backdrop-blur supports-[backdrop-filter]:bg-card/60">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-xl text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap h-6 w-6 text-primary"><path d="M4 14.75 15.3 3 13.5 9.25h6.7L8.7 21l1.8-6.25H4Z"/></svg>
                    Kizo
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('login') }}">
                        <x-ui.button variant="outline">Connexion</x-ui.button>
                    </a>
                    <a href="{{ route('register') }}">
                        <x-ui.button class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-primary-foreground">
                            S'inscrire
                        </x-ui.button>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center mb-16">
                <div class="mb-6">
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold text-primary bg-primary/10 border border-primary/20 mb-4">
                        🎮 Expérience d'apprentissage ludifiée
                    </span>
                </div>
                <h1 class="text-5xl sm:text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary via-secondary to-tertiary mb-6">
                    Maîtrisez votre apprentissage avec la ludification
                </h1>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto mb-8">
                    Kizo combine la puissance de la technique Pomodoro avec la ludification. Gagnez de l'XP, relevez des défis, débloquez des succès et regardez votre parcours d'apprentissage se transformer en une aventure épique.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}">
                        <x-ui.button size="lg" class="w-full sm:w-auto bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-primary-foreground font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap mr-2 h-5 w-5"><path d="M4 14.75 15.3 3 13.5 9.25h6.7L8.7 21l1.8-6.25H4Z"/></svg>
                            Commencer gratuitement aujourd'hui
                        </x-ui.button>
                    </a>
                    <a href="{{ route('login') }}">
                        <x-ui.button size="lg" variant="outline" class="w-full sm:w-auto">
                            Déjà un compte ?
                        </x-ui.button>
                    </a>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                @php
                    $features = [
                        [
                            'icon' => 'timer',
                            'title' => 'Minuteur Pomodoro',
                            'description' => 'Sessions d\'étude focalisées de 25 minutes scientifiquement prouvées avec des pauses intelligentes',
                            'gradient' => 'from-primary/10 to-primary/5 border-primary/20',
                        ],
                        [
                            'icon' => 'trending-up',
                            'title' => 'XP & Niveaux',
                            'description' => 'Gagnez des points d\'expérience à chaque session d\'étude et faites évoluer votre profil',
                            'gradient' => 'from-secondary/10 to-secondary/5 border-secondary/20',
                        ],
                        [
                            'icon' => 'trophy',
                            'title' => 'Défis',
                            'description' => 'Relevez des défis quotidiens et hebdomadaires pour repousser vos limites d\'apprentissage',
                            'gradient' => 'from-tertiary/10 to-tertiary/5 border-tertiary/20',
                        ],
                        [
                            'icon' => 'award',
                            'title' => 'Succès',
                            'description' => 'Débloquez des badges et des succès au fur et à mesure que vous atteignez vos objectifs d\'étude',
                            'gradient' => 'from-accent/10 to-accent/5 border-accent/20',
                        ],
                        [
                            'icon' => 'flame',
                            'title' => 'Suivi de série',
                            'description' => 'Créez des séries d\'études inarrêtables et maintenez votre motivation',
                            'gradient' => 'from-primary/10 to-primary/5 border-primary/20',
                        ],
                        [
                            'icon' => 'zap',
                            'title' => 'Stats en temps réel',
                            'description' => 'Suivez vos progrès avec des analyses et des aperçus détaillés',
                            'gradient' => 'from-secondary/10 to-secondary/5 border-secondary/20',
                        ],
                    ];
                @endphp

                @foreach ($features as $feature)
                    <div class="p-6 rounded-lg border-2 bg-gradient-to-br {{ $feature['gradient'] }}">
                        @if($feature['icon'] === 'timer')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-timer h-8 w-8 mb-3 text-primary"><line x1="10" x2="14" y1="2" y2="2"/><line x1="12" x2="15" y1="14" y2="11"/><circle cx="12" cy="14" r="8"/></svg>
                        @elseif($feature['icon'] === 'trending-up')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up h-8 w-8 mb-3 text-primary"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                        @elseif($feature['icon'] === 'trophy')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy h-8 w-8 mb-3 text-primary"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        @elseif($feature['icon'] === 'award')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award h-8 w-8 mb-3 text-primary"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                        @elseif($feature['icon'] === 'flame')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame h-8 w-8 mb-3 text-primary"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.21 1.146-3.027a1.722 1.722 0 0 1 2.354 0Z"/></svg>
                        @elseif($feature['icon'] === 'zap')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap h-8 w-8 mb-3 text-primary"><path d="M4 14.75 15.3 3 13.5 9.25h6.7L8.7 21l1.8-6.25H4Z"/></svg>
                        @endif
                        <h3 class="text-lg font-semibold mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-muted-foreground text-sm">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                @php
                    $stats = [
                        ['label' => 'Apprenants Actifs', 'value' => '10K+'],
                        ['label' => 'Sessions Terminées', 'value' => '500K+'],
                        ['label' => 'Heures Étudiées', 'value' => '100K+'],
                    ];
                @endphp

                @foreach ($stats as $stat)
                    <div class="text-center p-6 rounded-lg border border-border">
                        <div class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary mb-2">
                            {{ $stat['value'] }}
                        </div>
                        <p class="text-muted-foreground">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>

            <!-- CTA Section -->
            <div class="text-center bg-gradient-to-r from-primary/10 via-secondary/10 to-tertiary/10 rounded-lg border-2 border-primary/20 p-12">
                <h2 class="text-3xl font-bold mb-4">Prêt à transformer votre apprentissage ?</h2>
                <p class="text-muted-foreground mb-6">Rejoignez des milliers d'étudiants maîtrisant leurs sujets avec la ludification.</p>
                <a href="{{ route('register') }}">
                    <x-ui.button size="lg" class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-primary-foreground font-semibold">
                        Commencer gratuitement
                    </x-ui.button>
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-border bg-card/50 mt-16 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-muted-foreground">
                <p>&copy; 2024 Kizo Study Timer. Tous droits réservés. | Créé avec passion pour les apprenants du monde entier.</p>
            </div>
        </footer>
    </div>
</x-layouts.app>
