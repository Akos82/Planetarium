@extends('layouts.app')

@section('title', __('booking.page_title'))
@section('breadcrumb'){{ __('booking.breadcrumb') }}@endsection

@section('content')
<div class="py-8 md:py-14 px-4">
    <div class="max-w-6xl mx-auto">

        <div class="text-center mb-8 md:mb-10">
            <h1 class="text-2xl md:text-4xl font-bold text-gray-800 mb-3">{{ __('booking.title') }}</h1>
            <p class="text-gray-500 max-w-xl mx-auto">{{ __('booking.subtitle') }}</p>
        </div>

@auth
@if(auth()->user()->is_admin)
{{-- ===== ADMIN NÉZET ===== --}}
<div class="grid lg:grid-cols-3 gap-6 md:gap-8">

    {{-- Bal: Foglalások listája --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white border border-gray-200 rounded-2xl p-4 md:p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ __('booking.admin_title') }}
            </h2>

            @if(isset($bookings) && $bookings->count())
            <div class="space-y-3">
                @foreach($bookings->groupBy(fn($b) => $b->booking_date->format('Y-m-d')) as $date => $dayBookings)
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('Y. F j., l') }}
                    </p>
                    @foreach($dayBookings as $b)
                    <div class="flex items-start gap-4 border rounded-xl p-4 mb-2
                                {{ $b->status === 'confirmed' ? 'border-green-200 bg-green-50' : 'border-amber-200 bg-amber-50' }}">
                        <div class="flex-shrink-0 w-14 text-center">
                            <span class="text-lg font-bold {{ $b->status === 'confirmed' ? 'text-green-700' : 'text-amber-700' }}">
                                {{ substr($b->booking_time, 0, 5) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm">{{ $b->group_name }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $b->contact_name }} · {{ $b->contact_phone }}</p>
                            <p class="text-xs text-gray-500">{{ $b->contact_email }}</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-gray-600 rounded-full px-2.5 py-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20H5a2 2 0 01-2-2V8a2 2 0 012-2h10M17 20l4-4m-4 4l-4-4"/></svg>
                                    {{ $b->group_size }} {{ __('booking.persons') }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-xs rounded-full px-2.5 py-0.5
                                             {{ $b->with_presentation ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $b->with_presentation ? __('booking.admin_with_pres') : __('booking.admin_without_pres') }}
                                </span>
                                @if($b->show)
                                <span class="inline-flex items-center gap-1 text-xs bg-purple-100 text-purple-700 rounded-full px-2.5 py-0.5">
                                    {{ $b->show->title }}
                                </span>
                                @endif
                                <span class="inline-flex items-center gap-1 text-xs rounded-full px-2.5 py-0.5
                                             {{ $b->status === 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $b->status === 'confirmed' ? __('booking.admin_confirmed') : __('booking.admin_pending') }}
                                </span>
                            </div>
                            @if($b->notes)
                            <p class="text-xs text-gray-400 italic mt-1.5">"{{ $b->notes }}"</p>
                            @endif
                        </div>
                        <a href="{{ url('/admin/bookings/' . $b->id . '/edit') }}"
                           class="flex-shrink-0 text-xs text-brand-600 hover:underline font-medium mt-1">
                            {{ __('booking.admin_edit') }}
                        </a>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-10 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-sm">{{ __('booking.admin_no_bookings') }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Jobb: Naptár --}}
    <div class="space-y-5">
        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
            <h3 class="font-bold text-gray-800 mb-4 text-sm">{{ __('booking.admin_calendar') }}</h3>
            <div class="flex items-center justify-between mb-3">
                <button id="prev-month" class="w-8 h-8 rounded-lg border border-gray-200 hover:bg-gray-50 flex items-center justify-center transition text-gray-500">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <h2 id="calendar-title" class="text-sm font-bold text-gray-800"></h2>
                <button id="next-month" class="w-8 h-8 rounded-lg border border-gray-200 hover:bg-gray-50 flex items-center justify-center transition text-gray-500">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <div class="grid grid-cols-7 mb-1">
                @foreach(explode(',', __('booking.days_short')) as $d)
                    <div class="text-center text-xs font-semibold text-gray-400 py-1">{{ $d }}</div>
                @endforeach
            </div>
            <div id="calendar-grid" class="grid grid-cols-7 gap-0.5"></div>
            <div class="flex flex-col gap-1.5 mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-brand-500 inline-block"></span>{{ __('booking.legend_free') }}</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-400 inline-block"></span>{{ __('booking.legend_partial') }}</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>{{ __('booking.legend_full') }}</span>
            </div>
        </div>

        {{-- Nap részletei --}}
        <div id="day-detail" class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hidden">
            <h3 class="font-bold text-gray-800 mb-2 text-sm" id="detail-date"></h3>
            <div id="detail-bookings" class="space-y-2 text-sm"></div>
        </div>

        <a href="{{ url('/admin/bookings') }}"
           class="flex items-center justify-center gap-2 w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-3 rounded-xl transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
            {{ __('booking.admin_manage') }}
        </a>
    </div>
</div>
@else
{{-- ===== FELHASZNÁLÓ NÉZET ===== --}}

        {{-- Sikeres foglalás --}}
        @if(session('success'))
        <div class="bg-green-50 border border-green-300 rounded-xl p-4 mb-8 flex gap-3 items-start">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
        @endif

        {{-- Általános hiba --}}
        @if($errors->has('booking_time'))
        <div class="bg-red-50 border border-red-300 rounded-xl p-4 mb-8 flex gap-3 items-start">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <p class="text-red-800">{{ $errors->first('booking_time') }}</p>
        </div>
        @endif

        {{-- Figyelmeztetés --}}
        <div class="bg-amber-50 border border-amber-300 rounded-xl p-4 mb-8 flex gap-3 items-start">
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <p class="text-amber-800 text-sm"><strong>{{ __('booking.alert') }}</strong> {{ __('booking.alert_text') }}</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-6 md:gap-8">

            {{-- ===== BAL: FORM ===== --}}
            {{-- Mobilon a sidebar (naptár) jelenik meg először, ld. order-2/order-1 --}}
            <div class="lg:col-span-2 space-y-6 order-2 lg:order-1">

                <form method="POST" action="{{ route('booking.store') }}" id="booking-form">
                    @csrf

                    {{-- 1. Kapcsolattartói adatok --}}
                    <div class="bg-white border border-gray-200 rounded-2xl p-4 md:p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                            <span class="w-7 h-7 bg-brand-600 text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                            {{ __('booking.form_section1') }}
                        </h2>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.form_name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="contact_name" value="{{ old('contact_name', Auth::user()->name) }}" required
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                @error('contact_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.form_email') }} <span class="text-red-500">*</span></label>
                                <input type="email" name="contact_email" value="{{ old('contact_email', Auth::user()->email) }}" required
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 transition bg-gray-50" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.form_phone') }} <span class="text-red-500">*</span></label>
                                <input type="tel" name="contact_phone" value="{{ old('contact_phone') }}" required
                                       placeholder="+40 7xx xxx xxx"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                @error('contact_phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.form_org') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="group_name" value="{{ old('group_name') }}" required
                                       placeholder="{{ __('booking.form_org_placeholder') }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                <p class="text-xs text-gray-400 mt-1">{{ __('booking.form_org_hint') }}</p>
                                @error('group_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- 2. Látogatás részletei --}}
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                            <span class="w-7 h-7 bg-brand-600 text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            {{ __('booking.form_section2') }}
                        </h2>
                        <div class="grid md:grid-cols-2 gap-6">

                            {{-- Dátum --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.form_date_label') }} <span class="text-red-500">*</span></label>
                                <input type="date" name="booking_date" value="{{ old('booking_date') }}" required
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                <p class="text-xs text-gray-400 mt-1">{{ __('booking.form_date_hint') }}</p>
                                @error('booking_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Létszám --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.form_size_label') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="group_size" value="{{ old('group_size') }}" required min="1" max="27"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                <p class="text-xs text-gray-400 mt-1">{!! __('booking.form_size_hint') !!}</p>
                                @error('group_size')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Időpont --}}
                        <div class="mt-5">
                            <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('booking.form_time_label') }} <span class="text-red-500">*</span></label>
                            <p class="text-xs text-gray-500 mb-3">{!! __('booking.form_time_hint') !!}</p>
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                                @foreach(['08:00','09:00','10:00','11:00','12:00','16:00','17:00','18:00'] as $slot)
                                <label class="flex items-center justify-center border rounded-lg px-2 py-3 cursor-pointer text-sm font-medium transition
                                             {{ old('booking_time') === $slot ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-gray-200 hover:border-brand-400 hover:bg-brand-50 text-gray-700' }}
                                             has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 has-[:checked]:text-brand-700">
                                    <input type="radio" name="booking_time" value="{{ $slot }}" class="sr-only" {{ old('booking_time') === $slot ? 'checked' : '' }}>
                                    {{ $slot }}
                                </label>
                                @endforeach
                                <label class="flex items-center justify-center gap-2 border rounded-lg px-3 py-2.5 cursor-pointer text-sm font-medium transition col-span-3 sm:col-span-4
                                             {{ old('booking_time') && !in_array(old('booking_time'), ['08:00','09:00','10:00','11:00','12:00','16:00','17:00','18:00']) ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-gray-200 hover:border-brand-400 hover:bg-brand-50 text-gray-700' }}
                                             has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 has-[:checked]:text-brand-700">
                                    <input type="radio" name="booking_time" value="other" id="time-other-radio" class="sr-only" {{ old('booking_time') && !in_array(old('booking_time'), ['08:00','09:00','10:00','11:00','12:00','16:00','17:00','18:00']) ? 'checked' : '' }}>
                                    {{ __('booking.form_other_time') }}
                                </label>
                            </div>
                            {{-- Egyéb időpont input --}}
                            <div id="other-time-box" class="{{ old('booking_time') && !in_array(old('booking_time'), ['08:00','09:00','10:00','11:00','12:00','16:00','17:00','18:00']) ? '' : 'hidden' }} mt-3">
                                <input type="time" id="other-time-input" placeholder="pl. 14:30"
                                       class="border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 transition"
                                       value="{{ old('booking_time') && !in_array(old('booking_time'), ['08:00','09:00','10:00','11:00','12:00','16:00','17:00','18:00']) ? old('booking_time') : '' }}">
                                <p class="text-xs text-gray-400 mt-1">{{ __('booking.form_other_time_hint') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Vetítés típusa --}}
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                            <span class="w-7 h-7 bg-brand-600 text-white rounded-full flex items-center justify-center text-xs font-bold">3</span>
                            {{ __('booking.form_section3') }}
                        </h2>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <label class="flex items-start gap-4 border-2 rounded-xl p-4 cursor-pointer transition
                                         {{ old('with_presentation', '1') === '1' ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:border-brand-300' }}
                                         has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                                <input type="radio" name="with_presentation" value="1" class="mt-1 text-brand-600 focus:ring-brand-500" {{ old('with_presentation', '1') === '1' ? 'checked' : '' }}>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ __('booking.form_with_pres') }}</p>
                                    <p class="text-2xl font-bold text-brand-600 mt-1">300 lej</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ __('booking.form_with_pres_desc') }}</p>
                                </div>
                            </label>
                            <label class="flex items-start gap-4 border-2 rounded-xl p-4 cursor-pointer transition
                                         {{ old('with_presentation') === '0' ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:border-brand-300' }}
                                         has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                                <input type="radio" name="with_presentation" value="0" class="mt-1 text-brand-600 focus:ring-brand-500" {{ old('with_presentation') === '0' ? 'checked' : '' }}>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ __('booking.form_without_pres') }}</p>
                                    <p class="text-2xl font-bold text-brand-600 mt-1">250 lej</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ __('booking.form_without_pres_desc') }}</p>
                                </div>
                            </label>
                        </div>
                        @error('with_presentation')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
                    </div>

                    {{-- 4. Előadás kiválasztása --}}
                    @if($shows->isNotEmpty())
                    <div class="bg-white border border-gray-200 rounded-2xl p-4 md:p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-800 mb-2 flex items-center gap-2">
                            <span class="w-7 h-7 bg-brand-600 text-white rounded-full flex items-center justify-center text-xs font-bold">4</span>
                            {{ __('booking.form_section4') }}
                        </h2>
                        <p class="text-sm text-gray-500 mb-5">{{ __('booking.form_show_subtitle') }}</p>

                        <input type="hidden" name="show_id" id="show_id_input" value="{{ old('show_id', '') }}">
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3" id="show-cards">

                            @foreach($shows as $show)
                            <div class="show-card relative border-2 rounded-xl cursor-pointer transition select-none
                                        {{ old('show_id') == $show->id ? 'border-brand-600 bg-brand-50' : 'border-gray-200 hover:border-gray-300' }}"
                                 data-value="{{ $show->id }}">
                                <div class="flex items-center gap-3 p-3 min-h-[72px]">
                                    @if($show->image)
                                        <img src="{{ asset('storage/' . $show->image) }}" alt="{{ $show->title }}"
                                             class="w-12 h-12 object-cover rounded-lg flex-shrink-0">
                                    @else
                                        <div class="w-12 h-12 bg-brand-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3" fill="currentColor" class="opacity-50"/></svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0 pr-5">
                                        <p class="font-medium text-gray-800 text-sm leading-tight line-clamp-2">{{ $show->title }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $show->duration_minutes }} {{ __('shows.minutes') }}</p>
                                    </div>
                                </div>
                                <div class="show-check absolute top-2 right-2 w-5 h-5 rounded-full bg-brand-600 items-center justify-center
                                            {{ old('show_id') == $show->id ? 'flex' : 'hidden' }}">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- 5. Megjegyzés --}}
                    <div class="bg-white border border-gray-200 rounded-2xl p-4 md:p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 bg-brand-600 text-white rounded-full flex items-center justify-center text-xs font-bold">5</span>
                            {{ __('booking.form_section5') }}
                            <span class="text-sm font-normal text-gray-400 ml-1">{{ __('booking.form_optional') }}</span>
                        </h2>
                        <textarea name="notes" rows="3" placeholder="{{ __('booking.form_notes_ph') }}"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 transition resize-none">{{ old('notes') }}</textarea>
                    </div>

                    {{-- Beküldés --}}
                    <button type="submit"
                            class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-4 rounded-2xl transition text-base flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('booking.form_submit') }}
                    </button>
                </form>

            </div>

            {{-- ===== JOBB: Naptár + info ===== --}}
            {{-- Mobilon ez kerül felülre (order-1), desktopon jobbra (order-2) --}}
            <div class="space-y-5 order-1 lg:order-2">

                {{-- Naptár --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                    <h3 class="font-bold text-gray-800 mb-4 text-sm">{{ __('booking.calendar_free_slots') }}</h3>
                    <div class="flex items-center justify-between mb-3">
                        <button id="prev-month" class="w-8 h-8 rounded-lg border border-gray-200 hover:bg-gray-50 flex items-center justify-center transition text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <h2 id="calendar-title" class="text-sm font-bold text-gray-800"></h2>
                        <button id="next-month" class="w-8 h-8 rounded-lg border border-gray-200 hover:bg-gray-50 flex items-center justify-center transition text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-7 mb-1">
                        @foreach(explode(',', __('booking.days_short')) as $d)
                            <div class="text-center text-xs font-semibold text-gray-400 py-1">{{ $d }}</div>
                        @endforeach
                    </div>
                    <div id="calendar-grid" class="grid grid-cols-7 gap-0.5"></div>
                    <div class="flex flex-col gap-1.5 mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-brand-500 inline-block"></span>{{ __('booking.legend_free') }}</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-400 inline-block"></span>{{ __('booking.legend_partial') }}</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>{{ __('booking.legend_full') }}</span>
                    </div>
                </div>

                {{-- Foglalt nap részletei --}}
                <div id="day-detail" class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hidden">
                    <h3 class="font-bold text-gray-800 mb-2 text-sm" id="detail-date"></h3>
                    <div id="detail-bookings" class="space-y-2 text-sm"></div>
                </div>

                {{-- Díjak --}}
                <div class="bg-brand-50 border border-brand-200 rounded-xl p-5 text-sm">
                    <h4 class="font-bold text-brand-800 mb-3">{{ __('booking.fees_title') }}</h4>
                    <div class="space-y-2 text-brand-700">
                        <div class="flex justify-between items-center">
                            <span>{{ __('booking.fee_no_pres') }}</span>
                            <span class="font-bold">250 lej</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-brand-200 pt-2">
                            <span>{{ __('booking.fee_with_pres') }}</span>
                            <span class="font-bold">300 lej</span>
                        </div>
                    </div>
                </div>

                {{-- Fontos infók --}}
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 text-sm text-gray-500">
                    <p class="font-semibold text-gray-700 mb-2">{{ __('booking.important_title') }}</p>
                    <ul class="space-y-1.5">
                        <li class="flex gap-2"><span class="text-brand-500">•</span>{{ __('booking.important_max') }}</li>
                        <li class="flex gap-2"><span class="text-brand-500">•</span>{{ __('booking.important_hours') }}</li>
                        <li class="flex gap-2"><span class="text-brand-500">•</span>{{ __('booking.important_closed') }}</li>
                        <li class="flex gap-2 mt-2"><span class="text-brand-500">•</span>
                            <a href="mailto:planetarium@uni.sapientia.ro" class="text-brand-600 hover:underline">planetarium@uni.sapientia.ro</a>
                        </li>
                        <li class="flex gap-2"><span class="text-brand-500">•</span>
                            <a href="tel:+40724517526" class="text-brand-600 hover:underline">0724 517 526</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
@endif
@endauth
@endsection

@push('scripts')
<script>
const IS_ADMIN = {{ auth()->user()->is_admin ? 'true' : 'false' }};
const TRANS = {
    occupied:       '{{ __("booking.js_occupied") }}',
    freeDay:        '{{ __("booking.js_free_day") }}',
    otherTimeAlert: '{{ __("booking.js_other_time_alert") }}',
    persons:        '{{ __("booking.persons") }}',
};

// ---- Csak felhasználói nézetben ----
if (!IS_ADMIN) {
    // Vetítés típusa kártya kijelölés
    const presLabels = document.querySelectorAll('input[name="with_presentation"]');
    function updatePresCards() {
        presLabels.forEach(radio => {
            const label = radio.closest('label');
            if (radio.checked) {
                label.classList.add('border-brand-500', 'bg-brand-50');
                label.classList.remove('border-gray-200');
            } else {
                label.classList.remove('border-brand-500', 'bg-brand-50');
                label.classList.add('border-gray-200');
            }
        });
    }
    presLabels.forEach(radio => radio.addEventListener('change', updatePresCards));
    updatePresCards();

    // Előadás kártya kijelölés
    document.querySelectorAll('.show-card').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.show-card').forEach(c => {
                c.classList.remove('border-brand-600', 'bg-brand-50');
                c.classList.add('border-gray-200');
                c.querySelector('.show-check').classList.replace('flex', 'hidden');
            });
            card.classList.add('border-brand-600', 'bg-brand-50');
            card.classList.remove('border-gray-200');
            card.querySelector('.show-check').classList.replace('hidden', 'flex');
            document.getElementById('show_id_input').value = card.dataset.value;
        });
    });

    // Időpont egyéb logika
    document.querySelectorAll('input[name="booking_time"]').forEach(radio => {
        radio.addEventListener('change', () => {
            const box = document.getElementById('other-time-box');
            if (radio.value === 'other' && radio.checked) {
                box.classList.remove('hidden');
            } else {
                box.classList.add('hidden');
            }
        });
    });

    const bookingForm = document.getElementById('booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            const otherRadio = document.getElementById('time-other-radio');
            if (otherRadio && otherRadio.checked) {
                const otherVal = document.getElementById('other-time-input').value;
                if (!otherVal) {
                    e.preventDefault();
                    alert(TRANS.otherTimeAlert);
                    return;
                }
                otherRadio.value = otherVal;
            }
        });
    }
}

// ---- Naptár (mindkét nézetben) ----
const MONTHS = '{{ __("booking.months") }}'.split(',');
const TODAY = new Date();
let currentYear = TODAY.getFullYear();
let currentMonth = TODAY.getMonth();
let bookedDates = {};

async function loadBookedDates() {
    try {
        const res = await fetch('{{ route('api.booked-dates') }}');
        const data = await res.json();
        bookedDates = {};
        data.forEach(b => {
            if (!bookedDates[b.date]) bookedDates[b.date] = [];
            bookedDates[b.date].push(b);
        });
    } catch(e) {}
    renderCalendar();
}

function renderCalendar() {
    const title = document.getElementById('calendar-title');
    const grid = document.getElementById('calendar-grid');
    title.textContent = `${currentYear}. ${MONTHS[currentMonth]}`;
    grid.innerHTML = '';

    const firstDay = new Date(currentYear, currentMonth, 1);
    const startOffset = (firstDay.getDay() + 6) % 7;
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    for (let i = 0; i < startOffset; i++) {
        grid.appendChild(document.createElement('div'));
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${currentYear}-${String(currentMonth+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
        const date = new Date(currentYear, currentMonth, day);
        const isPast = date < new Date(TODAY.getFullYear(), TODAY.getMonth(), TODAY.getDate());
        const isToday = date.toDateString() === TODAY.toDateString();
        const isWeekend = date.getDay() === 0 || date.getDay() === 6;
        const bookings = bookedDates[dateStr] || [];
        const OPEN = [8,9,10,11,12,13,14,15,16,17,18,19];
        const taken = bookings.map(b => parseInt(b.time));
        const isFull = bookings.length > 0 && OPEN.every(h => taken.includes(h));

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = day;
        let cls = 'aspect-square rounded text-xs font-medium flex items-center justify-center transition ';
        if (isToday) cls += 'ring-2 ring-brand-500 ';

        if (isWeekend && !bookings.length) {
            cls += 'text-gray-300 cursor-default';
        } else if (isPast && !bookings.length && !IS_ADMIN) {
            cls += 'text-gray-300 cursor-default';
        } else if (isFull) {
            cls += 'bg-red-100 text-red-600 hover:bg-red-200 cursor-pointer';
        } else if (bookings.length > 0) {
            cls += 'bg-amber-100 text-amber-700 hover:bg-amber-200 cursor-pointer';
        } else if (isPast) {
            cls += 'text-gray-300 cursor-default';
        } else {
            cls += 'bg-brand-50 text-brand-700 hover:bg-brand-100 cursor-pointer';
        }
        btn.className = cls;

        const isClickable = bookings.length > 0 || (!isPast && !isWeekend);
        if (isClickable) {
            btn.addEventListener('click', () => {
                const dateInput = document.querySelector('input[name="booking_date"]');
                if (dateInput) dateInput.value = dateStr;
                showDetail(dateStr, day);
            });
        }
        grid.appendChild(btn);
    }
}

function showDetail(dateStr, day) {
    const panel = document.getElementById('day-detail');
    document.getElementById('detail-date').textContent = `${currentYear}. ${MONTHS[currentMonth]} ${day}.`;
    const div = document.getElementById('detail-bookings');
    if (bookedDates[dateStr] && bookedDates[dateStr].length) {
        div.innerHTML = bookedDates[dateStr].map(b =>
            IS_ADMIN
            ? `<div class="flex gap-2 items-start p-2 bg-red-50 border border-red-100 rounded-lg text-xs">
                <svg class="w-3.5 h-3.5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div><span class="font-bold">${b.time.substring(0,5)}</span> – ${b.group} <span class="text-gray-400">(${b.group_size} ${TRANS.persons})</span></div>
               </div>`
            : `<div class="flex gap-2 items-center p-2 bg-red-50 border border-red-100 rounded-lg text-xs">
                <svg class="w-3.5 h-3.5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-medium">${b.time.substring(0,5)}</span> – ${TRANS.occupied} (${b.group_size} ${TRANS.persons})
               </div>`
        ).join('');
    } else {
        div.innerHTML = `<p class="text-brand-600 text-xs font-medium flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>${TRANS.freeDay}</p>`;
    }
    panel.classList.remove('hidden');
}

document.getElementById('prev-month').addEventListener('click', () => {
    if (currentMonth === 0) { currentMonth = 11; currentYear--; } else currentMonth--;
    renderCalendar();
});
document.getElementById('next-month').addEventListener('click', () => {
    if (currentMonth === 11) { currentMonth = 0; currentYear++; } else currentMonth++;
    renderCalendar();
});

loadBookedDates();
</script>
@endpush
