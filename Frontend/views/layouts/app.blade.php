<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Planetárium – Sapientia EMTE')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.11.0/css/flag-icons.min.css"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-800 font-sans">

    {{-- Felső sáv (Sapientia branding) --}}
    <div class="bg-brand-600 text-white text-xs py-1.5 px-4 hidden md:block">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <span>Sapientia Erdélyi Magyar Tudományegyetem – Csíkszeredai Kar</span>
            <a href="mailto:planetarium@uni.sapientia.ro" class="hover:underline">planetarium@uni.sapientia.ro</a>
        </div>
    </div>

    {{-- Navigáció --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-brand-600 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="8" stroke="currentColor"/>
                        <circle cx="12" cy="12" r="3" fill="currentColor"/>
                        <ellipse cx="12" cy="12" rx="8" ry="3" stroke="currentColor" transform="rotate(-25 12 12)"/>
                    </svg>
                </div>
                <div class="leading-tight">
                    <p class="font-bold text-gray-800 text-sm">Planetárium</p>
                    <p class="text-brand-600 text-xs font-medium">Sapientia EMTE</p>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('home') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">{{ __('nav.home') }}</a>
                <a href="{{ route('shows') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('shows') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">{{ __('nav.shows') }}</a>
                <a href="{{ route('booking') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('booking') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">{{ __('nav.booking') }}</a>
                <a href="{{ route('contact') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('contact') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">{{ __('nav.contact') }}</a>
                @auth
                @if(!auth()->user()->is_admin)
                <a href="{{ route('booking') }}" class="ml-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">{{ __('nav.book_now') }}</a>
                @endif
                @else
                <a href="{{ route('login') }}" class="ml-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">{{ __('layout.login') }}</a>
                @endauth

                {{-- Felhasználó / Kijelentkezés --}}
                @auth
                <div class="relative ml-2 border-l border-gray-200 pl-3">
                    <button id="user-btn" class="flex items-center gap-1.5 px-2 py-1.5 rounded-lg hover:bg-gray-100 transition text-sm text-gray-700">
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1118.88 6.196M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="user-menu" class="hidden absolute right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-lg p-1 z-50 min-w-[140px]">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/></svg>
                                {{ __('layout.logout') }}
                            </button>
                        </form>
                    </div>
                </div>
                @endauth

                {{-- Nyelváltó dropdown --}}
                @php $currentLocale = app()->getLocale(); @endphp
                <div class="relative ml-3 border-l border-gray-200 pl-3">
                    <button id="lang-btn" class="flex items-center gap-1.5 px-2 py-1.5 rounded-lg hover:bg-gray-100 transition">
                        <span class="fi fi-{{ $currentLocale === 'ro' ? 'ro' : 'hu' }} rounded-sm" style="width:22px;height:16px;display:inline-block;background-size:cover;"></span>
                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="lang-menu" class="hidden absolute right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-lg p-2 z-50">
                        <div class="flex gap-1.5">
                            <a href="{{ route('language.switch', 'hu') }}"
                               class="flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition {{ $currentLocale === 'hu' ? 'ring-2 ring-brand-500 bg-brand-50' : '' }}"
                               title="Magyar">
                                <span class="fi fi-hu rounded-sm" style="width:26px;height:19px;display:inline-block;background-size:cover;"></span>
                            </a>
                            <a href="{{ route('language.switch', 'ro') }}"
                               class="flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition {{ $currentLocale === 'ro' ? 'ring-2 ring-brand-500 bg-brand-50' : '' }}"
                               title="Română">
                                <span class="fi fi-ro rounded-sm" style="width:26px;height:19px;display:inline-block;background-size:cover;"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-500 hover:text-gray-800" aria-label="Menü">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        {{-- Mobil menü --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white">
            <div class="px-4 py-3 space-y-1 text-sm font-medium">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('home') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50' }}">{{ __('nav.home') }}</a>
                <a href="{{ route('shows') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('shows') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50' }}">{{ __('nav.shows') }}</a>
                <a href="{{ route('booking') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('booking') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50' }}">{{ __('nav.booking') }}</a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('contact') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50' }}">{{ __('nav.contact') }}</a>
                <div class="flex items-center gap-2 px-3 py-2 border-t border-gray-100 mt-1 pt-3">
                    <span class="text-xs text-gray-400">Nyelv / Limbă:</span>
                    <a href="{{ route('language.switch', 'hu') }}"
                       class="flex items-center justify-center w-9 h-9 rounded-lg hover:bg-gray-100 transition {{ app()->getLocale() === 'hu' ? 'ring-2 ring-brand-500 bg-brand-50' : '' }}"
                       title="Magyar">
                        <span class="fi fi-hu rounded-sm" style="width:24px;height:18px;display:inline-block;background-size:cover;"></span>
                    </a>
                    <a href="{{ route('language.switch', 'ro') }}"
                       class="flex items-center justify-center w-9 h-9 rounded-lg hover:bg-gray-100 transition {{ app()->getLocale() === 'ro' ? 'ring-2 ring-brand-500 bg-brand-50' : '' }}"
                       title="Română">
                        <span class="fi fi-ro rounded-sm" style="width:24px;height:18px;display:inline-block;background-size:cover;"></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Kenyérmorzsa --}}
    @hasSection('breadcrumb')
    <div class="bg-gray-50 border-b border-gray-200 py-2 px-4">
        <div class="max-w-6xl mx-auto text-sm text-gray-500 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-brand-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </a>
            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            @yield('breadcrumb')
        </div>
    </div>
    @endif

    <main>
        @yield('content')
    </main>

    {{-- Lábléc --}}
    <footer class="bg-gray-800 text-gray-300 mt-10 md:mt-16">
        <div class="max-w-6xl mx-auto px-4 py-8 md:py-10 grid md:grid-cols-3 gap-6 md:gap-8">
            <div class="text-center md:text-left">
                <div class="flex items-center gap-2 mb-3 justify-center md:justify-start">
                    <div class="w-8 h-8 bg-brand-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3" fill="currentColor"/></svg>
                    </div>
                    <span class="font-bold text-white">Planetárium</span>
                </div>
                <p class="text-sm text-gray-400">Sapientia Erdélyi Magyar Tudományegyetem<br>Csíkszeredai Kar</p>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-3 text-sm">{{ __('footer.links') }}</h4>
                <ul class="space-y-1.5 text-sm">
                    <li><a href="{{ route('shows') }}" class="hover:text-brand-400 transition">{{ __('nav.shows') }}</a></li>
                    <li><a href="{{ route('booking') }}" class="hover:text-brand-400 transition">{{ __('nav.booking') }}</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-brand-400 transition">{{ __('nav.contact') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-3 text-sm">{{ __('footer.contact') }}</h4>
                <ul class="space-y-1.5 text-sm text-gray-400">
                    <li>Bicsak Károly</li>
                    <li><a href="tel:+40724517526" class="hover:text-brand-400 transition">0724 517 526</a></li>
                    <li><a href="mailto:planetarium@uni.sapientia.ro" class="hover:text-brand-400 transition">planetarium@uni.sapientia.ro</a></li>
                    <li class="pt-1">Csíkszereda, Szabadság tér 1., 6. em.</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 py-4 px-4">
            <p class="max-w-6xl mx-auto text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} Sapientia Erdélyi Magyar Tudományegyetem – Planetárium. {{ __('footer.rights') }}
                &nbsp;·&nbsp;
                <a href="{{ route('privacy') }}" class="hover:text-brand-400 transition">{{ __('layout.privacy') }}</a>
            </p>
        </div>
    </footer>

    {{-- Cookie banner --}}
    <div id="cookie-banner" class="hidden fixed bottom-0 left-0 right-0 z-50 bg-gray-900 border-t border-gray-700 px-4 py-4 shadow-lg">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <p class="text-sm text-gray-300">
                {{ __('layout.cookie_text') }}
                <a href="{{ route('privacy') }}" class="text-brand-400 hover:underline ml-1">{{ __('layout.privacy') }}</a>
            </p>
            <button onclick="acceptCookies()" class="shrink-0 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold px-5 py-2 rounded-lg transition">
                {{ __('layout.cookie_accept') }}
            </button>
        </div>
    </div>

    <script>
        function acceptCookies() {
            localStorage.setItem('cookies_accepted', '1');
            document.getElementById('cookie-banner').classList.add('hidden');
        }
        if (!localStorage.getItem('cookies_accepted')) {
            document.getElementById('cookie-banner').classList.remove('hidden');
        }
    </script>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // User dropdown
        const userBtn  = document.getElementById('user-btn');
        const userMenu = document.getElementById('user-menu');
        if (userBtn && userMenu) {
            userBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userMenu.classList.toggle('hidden');
            });
            document.addEventListener('click', () => {
                userMenu.classList.add('hidden');
            });
        }

        // Nyelváltó dropdown
        const langBtn  = document.getElementById('lang-btn');
        const langMenu = document.getElementById('lang-menu');
        if (langBtn && langMenu) {
            langBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                langMenu.classList.toggle('hidden');
            });
            document.addEventListener('click', () => {
                langMenu.classList.add('hidden');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
