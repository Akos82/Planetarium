<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.reset_page_title') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: #0f172a; }
        .stars {
            position: fixed; inset: 0; z-index: 0;
            background-image:
                radial-gradient(1px 1px at 10% 15%, white 0%, transparent 100%),
                radial-gradient(1px 1px at 25% 40%, rgba(255,255,255,.7) 0%, transparent 100%),
                radial-gradient(1px 1px at 40% 10%, white 0%, transparent 100%),
                radial-gradient(1px 1px at 55% 60%, rgba(255,255,255,.8) 0%, transparent 100%),
                radial-gradient(1px 1px at 70% 25%, white 0%, transparent 100%),
                radial-gradient(1px 1px at 80% 75%, rgba(255,255,255,.6) 0%, transparent 100%),
                radial-gradient(1px 1px at 90% 45%, white 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 15% 70%, rgba(255,255,255,.9) 0%, transparent 100%),
                radial-gradient(1px 1px at 35% 85%, white 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 60% 90%, rgba(255,255,255,.7) 0%, transparent 100%),
                radial-gradient(1px 1px at 75% 5%, white 0%, transparent 100%),
                radial-gradient(1px 1px at 5% 50%, rgba(255,255,255,.8) 0%, transparent 100%);
        }
    </style>
</head>
<body class="h-full flex items-center justify-center font-sans" style="min-height:100vh;">
    <div class="stars"></div>

    <div class="relative z-10 px-6" style="width:100%;max-width:420px;">
        {{-- Logo + cím --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-brand-600 rounded-full mb-4 shadow-lg shadow-brand-900/50">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="8" stroke="currentColor"/>
                    <circle cx="12" cy="12" r="3" fill="currentColor"/>
                    <ellipse cx="12" cy="12" rx="8" ry="3" stroke="currentColor" transform="rotate(-25 12 12)"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">Planetárium</h1>
            <p class="text-slate-400 text-sm mt-1">Sapientia EMTE – Csíkszeredai Kar</p>
        </div>

        {{-- Kártya --}}
        <div class="bg-white rounded-2xl shadow-2xl shadow-black/40 p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">{{ __('auth.reset_title') }}</h2>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('auth.email') }}</label>
                    <input type="email" name="email" value="{{ old('email', $email) }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition"
                           placeholder="pelda@email.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('auth.new_password') }}
                        <span class="text-gray-400 font-normal">{{ __('auth.password_min') }}</span>
                    </label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition"
                           placeholder="••••••••">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('auth.password_confirm') }}</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition"
                           placeholder="••••••••">
                </div>

                <button type="submit"
                        class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 rounded-lg transition text-sm mt-2">
                    {{ __('auth.reset_btn') }}
                </button>
            </form>
        </div>

        <p class="text-center text-slate-500 text-xs mt-6">
            &copy; {{ date('Y') }} Sapientia Erdélyi Magyar Tudományegyetem
        </p>
    </div>
</body>
</html>
