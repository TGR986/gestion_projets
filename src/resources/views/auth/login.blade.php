<x-guest-layout>
    <div class="min-h-screen bg-slate-100 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 overflow-hidden rounded-2xl bg-white shadow-xl">
            
            <div class="hidden lg:flex flex-col justify-center bg-slate-800 text-white p-12">
                <div class="mb-6">
                    <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-2xl font-bold">
                        GP
                    </div>
                </div>

                <h1 class="text-3xl font-bold leading-tight">
                    Outil de gestion de projets
                </h1>

                <p class="mt-3 text-lg text-slate-200">
                    Administration supérieure des îles Wallis et Futuna
                </p>

                <p class="mt-8 text-sm leading-6 text-slate-300">
                    Suivi des projets, workflow par étapes et sous-étapes, dépôt de documents,
                    gestion des versions, validations et commentaires dans un environnement
                    simple, lisible et adapté aux services administratifs.
                </p>
            </div>

            <div class="p-8 sm:p-10 lg:p-12">
                <div class="mx-auto w-full max-w-md">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-slate-900">
                            Connexion
                        </h2>
                        <p class="mt-2 text-sm text-slate-600">
                            Connectez-vous pour accéder à la plateforme.
                        </p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="email" :value="'Adresse e-mail'" />
                            <x-text-input id="email" class="mt-1 block w-full"
                                          type="email"
                                          name="email"
                                          :value="old('email')"
                                          required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="'Mot de passe'" />
                            <x-text-input id="password" class="mt-1 block w-full"
                                          type="password"
                                          name="password"
                                          required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox"
                                       class="rounded border-slate-300 text-slate-700 shadow-sm focus:ring-slate-500"
                                       name="remember">
                                <span class="ms-2 text-sm text-slate-600">Se souvenir de moi</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-sm text-slate-600 underline hover:text-slate-900"
                                   href="{{ route('password.request') }}">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                        </div>

                        <div>
                            <button type="submit"
                                    class="inline-flex w-full justify-center rounded-xl bg-slate-800 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                                Se connecter
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 border-t border-slate-200 pt-6 text-xs text-slate-500">
                        Plateforme interne de gestion des projets et des validations.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>