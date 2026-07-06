@extends('layouts.app')

@section('title', 'Planetárium – Sapientia EMTE')

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-brand-700 to-brand-900 text-white py-12 md:py-20 px-4">
    <div class="max-w-5xl mx-auto text-center">
        <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-1.5 text-sm mb-6">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="4"/></svg>
            {{ __('home.badge') }}
        </div>
        <h1 class="text-4xl md:text-6xl font-bold mb-5 leading-tight">
            {{ __('home.title') }}
        </h1>
        <p class="text-lg md:text-xl text-brand-100 max-w-2xl mx-auto mb-8 leading-relaxed">
            {{ __('home.subtitle') }} <strong class="text-white">{{ __('home.subtitle_hours') }}</strong> {{ __('home.subtitle_end') }}
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('booking') }}" class="inline-flex items-center justify-center gap-2 bg-white text-brand-700 font-bold px-8 py-3.5 rounded-xl hover:bg-brand-50 transition shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ __('home.btn_book') }}
            </a>
            <a href="{{ route('shows') }}" class="inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-500 text-white font-semibold px-8 py-3.5 rounded-xl transition border border-brand-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __('home.btn_shows') }}
            </a>
        </div>
    </div>
</section>

{{-- Bevezető szöveg --}}
<section class="py-8 md:py-12 px-4 bg-white">
    <div class="max-w-4xl mx-auto">
        <div class="bg-brand-50 border-l-4 border-brand-600 rounded-r-xl p-6 text-gray-700 leading-relaxed">
            <p class="mb-3">
                {{ __('about.intro1') }} <strong>{{ __('about.intro1_hours') }}</strong> {{ __('about.intro1_end') }}
            </p>
            <p>
                {{ __('about.intro2') }} <strong>{{ __('about.intro2_bold') }}</strong>.
            </p>
        </div>
    </div>
</section>

{{-- Árak és infók --}}
<section class="pb-8 md:pb-16 px-4 bg-white">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">{{ __('home.fees_title') }}</h2>
        <div class="grid md:grid-cols-2 gap-6 mb-10">
            <div class="border-2 border-brand-500 rounded-2xl p-6 bg-brand-50">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-brand-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-brand-700">250 lej</p>
                        <p class="font-semibold text-gray-700 mt-1">{{ __('home.screening_name') }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ __('home.screening_desc') }}</p>
                    </div>
                </div>
                <a href="https://payments.sapientia.ro/hu/4DCUGL" class="mt-4 w-full flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                    {{ __('home.screening_btn') }}
                </a>
            </div>
            <div class="border-2 border-green-600 rounded-2xl p-6 bg-green-50">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-green-700 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-green-700">300 lej</p>
                        <p class="font-semibold text-gray-700 mt-1">{{ __('home.presentation_name') }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ __('home.presentation_desc') }}</p>
                    </div>
                </div>
                <a href="https://payments.sapientia.ro/hu/WPQBMJ" class="mt-4 w-full flex items-center justify-center gap-2 bg-green-700 hover:bg-green-800 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                    {{ __('home.presentation_btn') }}
                </a>
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-sm text-amber-800">
            <p class="font-semibold mb-1">{{ __('home.payment_title') }}</p>
            <p>{!! __('home.payment_desc') !!}</p>
        </div>
    </div>
</section>

{{-- Kiemelt infók --}}
<section class="py-8 md:py-14 px-4 bg-gray-50 border-y border-gray-200">
    <div class="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-100 text-center">
            <div class="w-10 h-10 md:w-12 md:h-12 bg-brand-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-lg md:text-2xl font-bold text-gray-800 mb-1">{{ __('home.capacity_stat') }}</p>
            <p class="text-gray-500 text-xs md:text-sm">{{ __('home.capacity_desc') }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-100 text-center">
            <div class="w-10 h-10 md:w-12 md:h-12 bg-brand-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-lg md:text-2xl font-bold text-gray-800 mb-1">{{ __('home.hours_stat') }}</p>
            <p class="text-gray-500 text-xs md:text-sm">{{ __('home.hours_desc') }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-100 text-center">
            <div class="w-10 h-10 md:w-12 md:h-12 bg-brand-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <p class="text-lg md:text-2xl font-bold text-gray-800 mb-1">{{ __('home.films_stat') }}</p>
            <p class="text-gray-500 text-xs md:text-sm">{{ __('home.films_desc') }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-100 text-center">
            <div class="w-10 h-10 md:w-12 md:h-12 bg-brand-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <p class="text-lg md:text-2xl font-bold text-gray-800 mb-1">6. {{ __('about.stat_floor') }}</p>
            <p class="text-gray-500 text-xs md:text-sm">Sapientia EMTE, Csíkszereda</p>
        </div>
    </div>
</section>

{{-- Filmeink --}}
<section class="py-8 md:py-14 px-4 bg-white">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ __('about.films_title') }}</h2>
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <p class="text-gray-600 mb-4 leading-relaxed">
                {!! __('about.films_desc') !!}
            </p>
            <a href="{{ route('shows') }}" class="inline-flex items-center gap-2 text-brand-600 hover:text-brand-700 font-semibold text-sm">
                {{ __('about.films_link') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- Fontos figyelmeztetés --}}
<section class="pb-8 md:pb-14 px-4 bg-white">
    <div class="max-w-3xl mx-auto text-center">
        <div class="bg-brand-700 text-white rounded-2xl p-8">
            <h2 class="text-xl md:text-2xl font-bold mb-3">
                {{ __('home.cta_title') }}
            </h2>
            <p class="text-brand-100 mb-6 text-sm">{{ __('home.cta_desc') }}</p>
            @if(!auth()->check() || !auth()->user()->is_admin)
            <a href="{{ route('booking') }}" class="inline-flex items-center gap-2 bg-white text-brand-700 font-bold px-8 py-3 rounded-xl hover:bg-brand-50 transition">
                {{ __('home.cta_btn') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            @endif
        </div>
    </div>
</section>

@endsection
