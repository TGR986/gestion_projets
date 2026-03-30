<nav x-data="{ open: false }" class="bg-slate-800 text-white shadow">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            <!-- Marque + menu desktop -->
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}"
                   class="text-base sm:text-lg font-semibold tracking-tight whitespace-nowrap">
                    InfosProjets-WF
                </a>

                <div class="hidden md:flex items-center gap-5 text-sm">
                    <a href="{{ route('dashboard') }}"
                       class="{{ request()->routeIs('dashboard') ? 'font-semibold text-white' : 'text-slate-200 hover:text-white' }} transition">
                        Dashboard
                    </a>

                    <a href="{{ route('projets.index') }}"
                       class="{{ request()->routeIs('projets.*') ? 'font-semibold text-white' : 'text-slate-200 hover:text-white' }} transition">
                        Projets
                    </a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}"
                           class="{{ request()->routeIs('admin.users.*') ? 'font-semibold text-white' : 'text-slate-200 hover:text-white' }} transition">
                            Utilisateurs
                        </a>
                    @endif
                </div>
            </div>

            <!-- Zone droite desktop -->
            <div class="hidden md:flex items-center gap-4">
                <div class="text-right">
                    <div class="text-sm font-medium leading-tight">
                        {{ Auth::user()->prenom ?? '' }} {{ Auth::user()->name }}
                    </div>
                    <div class="text-xs text-slate-300 leading-tight">
                        {{ Auth::user()->structure ?? Auth::user()->email }}
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center rounded-lg bg-slate-700 px-3 py-2 text-sm font-medium text-white hover:bg-slate-600 transition">
                        Déconnexion
                    </button>
                </form>
            </div>

            <!-- Bouton mobile -->
            <div class="md:hidden">
                <button
                    @click="open = !open"
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg p-2 text-slate-200 hover:bg-slate-700 hover:text-white focus:outline-none"
                    aria-controls="mobile-menu"
                    :aria-expanded="open.toString()"
                >
                    <span class="sr-only">Ouvrir le menu principal</span>

                    <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>

                    <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu mobile -->
    <div
        id="mobile-menu"
        x-show="open"
        x-cloak
        x-transition
        class="border-t border-slate-700 bg-slate-800 md:hidden"
    >
        <div class="space-y-1 px-4 py-4">
            <a href="{{ route('dashboard') }}"
               class="block rounded-lg px-3 py-3 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-slate-700 text-white' : 'text-slate-200 hover:bg-slate-700 hover:text-white' }}">
                Dashboard
            </a>

            <a href="{{ route('projets.index') }}"
               class="block rounded-lg px-3 py-3 text-sm font-medium {{ request()->routeIs('projets.*') ? 'bg-slate-700 text-white' : 'text-slate-200 hover:bg-slate-700 hover:text-white' }}">
                Projets
            </a>

            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.users.index') }}"
                   class="block rounded-lg px-3 py-3 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-slate-700 text-white' : 'text-slate-200 hover:bg-slate-700 hover:text-white' }}">
                    Utilisateurs
                </a>
            @endif
        </div>

        <div class="border-t border-slate-700 px-4 py-4">
            <div class="mb-3">
                <div class="text-sm font-medium text-white">
                    {{ Auth::user()->prenom ?? '' }} {{ Auth::user()->name }}
                </div>
                <div class="text-xs text-slate-300">
                    {{ Auth::user()->structure ?? Auth::user()->email }}
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-slate-700 px-4 py-3 text-sm font-medium text-white hover:bg-slate-600 transition">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>