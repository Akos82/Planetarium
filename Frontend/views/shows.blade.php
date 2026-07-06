@extends('layouts.app')

@section('title', __('shows.page_title'))
@section('breadcrumb'){{ __('shows.breadcrumb') }}@endsection

@section('content')
<div class="py-14 px-4">
    <div class="max-w-5xl mx-auto">

        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-3">{{ __('shows.title') }}</h1>
            <p class="text-gray-500 max-w-xl mx-auto">{{ __('shows.subtitle') }}</p>
        </div>

        @if($shows->isNotEmpty())

            {{-- Slideshow – kiemelt előadás --}}
            <div class="relative mb-12 rounded-2xl overflow-hidden border border-gray-200 shadow-sm bg-white" id="slideshow">
                @foreach($shows as $index => $show)
                <div class="slide {{ $index === 0 ? 'block' : 'hidden' }}" data-slide="{{ $index }}">
                    <div class="flex flex-col md:flex-row min-h-[320px]">
                        {{-- Bal oldal: Kép / Ikon --}}
                        <div class="md:w-2/5 bg-gradient-to-br from-brand-600 to-brand-800 flex items-center justify-center min-h-48 relative overflow-hidden">
                            @if($show->image)
                                <img src="{{ asset('storage/' . $show->image) }}" alt="{{ $show->title }}" class="absolute inset-0 w-full h-full object-cover">
                            @else
                                <div class="text-center text-white/60">
                                    <svg class="w-20 h-20 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3" fill="currentColor" class="opacity-70"/>
                                        <ellipse cx="12" cy="12" rx="9" ry="3.5" transform="rotate(-30 12 12)"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        {{-- Jobb oldal: Tartalom --}}
                        <div class="md:w-3/5 p-8 flex flex-col justify-center">
                            <div class="flex flex-wrap gap-2 mb-4">
                                @if($show->age_recommendation)
                                    <span class="bg-brand-100 text-brand-700 text-xs font-semibold px-3 py-1 rounded-full">{{ $show->age_recommendation }}</span>
                                @endif
                                <span class="bg-gray-100 text-gray-600 text-xs font-medium px-3 py-1 rounded-full">{{ $show->duration_minutes }} {{ __('shows.minutes') }}</span>
                                @if($show->language)
                                    @php
                                        $langKey = match($show->language) {
                                            'hu'   => 'shows.lang_hu',
                                            'ro'   => 'shows.lang_ro',
                                            'both' => 'shows.lang_both',
                                            default => null,
                                        };
                                    @endphp
                                    @if($langKey)
                                        <span class="bg-gray-100 text-gray-600 text-xs font-medium px-3 py-1 rounded-full">{{ __($langKey) }}</span>
                                    @endif
                                @endif
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-3">{{ $show->title }}</h2>
                            <p class="text-gray-600 leading-relaxed mb-6">{{ $show->description }}</p>
                            <div class="flex gap-3 flex-wrap">
                                @if($show->url)
                                    <a href="{{ $show->url }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-5 py-2.5 rounded-xl transition text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        {{ __('shows.details_btn') }}
                                    </a>
                                @endif
                                <a href="{{ route('booking') }}"
                                   class="inline-flex items-center gap-2 border border-brand-600 text-brand-600 hover:bg-brand-50 font-semibold px-5 py-2.5 rounded-xl transition text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ __('shows.book_btn') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                @if($shows->count() > 1)
                <button onclick="prevSlide()" class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/90 hover:bg-white shadow border border-gray-200 rounded-full flex items-center justify-center transition">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button onclick="nextSlide()" class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/90 hover:bg-white shadow border border-gray-200 rounded-full flex items-center justify-center transition">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                    @foreach($shows as $index => $show)
                        <button onclick="goToSlide({{ $index }})" class="slide-dot w-2 h-2 rounded-full transition {{ $index === 0 ? 'bg-brand-600' : 'bg-gray-300' }}" data-index="{{ $index }}"></button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Összes előadás kártyák --}}
            <h2 class="text-xl font-bold text-gray-700 mb-5">{{ __('shows.all_title') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($shows as $show)
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md hover:border-brand-300 transition group">
                    <div class="bg-gradient-to-br from-brand-50 to-brand-100 h-36 flex items-center justify-center">
                        @if($show->image)
                            <img src="{{ asset('storage/' . $show->image) }}" alt="{{ $show->title }}" class="h-full w-full object-cover">
                        @else
                            <svg class="w-10 h-10 text-brand-400" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3" fill="currentColor"/></svg>
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="flex flex-wrap gap-1.5 mb-2">
                            @if($show->age_recommendation)
                                <span class="bg-brand-50 text-brand-700 text-xs px-2 py-0.5 rounded font-medium">{{ $show->age_recommendation }}</span>
                            @endif
                            <span class="bg-gray-50 text-gray-500 text-xs px-2 py-0.5 rounded">{{ $show->duration_minutes }} {{ __('shows.minutes') }}</span>
                            @if($show->language)
                                @php
                                    $langKey = match($show->language) {
                                        'hu'   => 'shows.lang_hu',
                                        'ro'   => 'shows.lang_ro',
                                        'both' => 'shows.lang_both',
                                        default => null,
                                    };
                                @endphp
                                @if($langKey)
                                    <span class="bg-gray-50 text-gray-500 text-xs px-2 py-0.5 rounded">{{ __($langKey) }}</span>
                                @endif
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-800 mb-1.5 group-hover:text-brand-700 transition">{{ $show->title }}</h3>
                        <p class="text-gray-500 text-sm line-clamp-2 mb-3">{{ $show->description }}</p>
                        @if($show->url)
                            <a href="{{ $show->url }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 text-brand-600 hover:text-brand-700 text-sm font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                {{ __('shows.details_link') }}
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

        @else
            <div class="text-center py-20">
                <svg class="w-14 h-14 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/></svg>
                <p class="text-gray-500 text-lg">{{ __('shows.empty') }}</p>
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.slide-dot');

function showSlide(n) {
    slides.forEach(s => s.classList.add('hidden'));
    dots.forEach(d => { d.classList.remove('bg-brand-600'); d.classList.add('bg-gray-300'); });
    currentSlide = (n + slides.length) % slides.length;
    slides[currentSlide].classList.remove('hidden');
    dots[currentSlide].classList.remove('bg-gray-300');
    dots[currentSlide].classList.add('bg-brand-600');
}
function nextSlide() { showSlide(currentSlide + 1); }
function prevSlide() { showSlide(currentSlide - 1); }
function goToSlide(n) { showSlide(n); }
if (slides.length > 1) setInterval(nextSlide, 5000);
</script>
@endpush
