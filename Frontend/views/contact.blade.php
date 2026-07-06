@extends('layouts.app')

@section('title', __('contact.page_title'))
@section('breadcrumb'){{ __('contact.breadcrumb') }}@endsection

@section('content')
<div class="py-14 px-4">
    <div class="max-w-4xl mx-auto">

        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-3">{{ __('contact.title') }}</h1>
            <p class="text-gray-500">{{ __('contact.subtitle') }}</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            {{-- Kapcsolati adatok --}}
            <div class="space-y-4">
                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex gap-4 items-start">
                    <div class="w-10 h-10 bg-brand-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-0.5">{{ __('contact.label_contact') }}</p>
                        <p class="font-bold text-gray-800 text-lg">Bicsak Károly</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex gap-4 items-start">
                    <div class="w-10 h-10 bg-brand-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-0.5">{{ __('contact.label_phone') }}</p>
                        <a href="tel:+40724517526" class="font-semibold text-gray-800 hover:text-brand-600 transition text-lg">0724 517 526</a>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex gap-4 items-start">
                    <div class="w-10 h-10 bg-brand-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-0.5">{{ __('contact.label_email') }}</p>
                        <a href="mailto:planetarium@uni.sapientia.ro" class="font-semibold text-brand-600 hover:text-brand-700 transition">planetarium@uni.sapientia.ro</a>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex gap-4 items-start">
                    <div class="w-10 h-10 bg-brand-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-0.5">{{ __('contact.label_location') }}</p>
                        <p class="font-semibold text-gray-800">{{ __('contact.location_city') }}</p>
                        <p class="text-gray-500 text-sm">{{ __('contact.location_address') }}</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex gap-4 items-start">
                    <div class="w-10 h-10 bg-brand-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-0.5">{{ __('contact.label_hours') }}</p>
                        <p class="font-semibold text-gray-800">{{ __('contact.hours_value') }}</p>
                        <p class="text-gray-500 text-sm">{{ __('contact.hours_note') }}</p>
                    </div>
                </div>
            </div>

            {{-- Térkép + Foglalás --}}
            <div class="space-y-4">
                <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm h-72">
                    <iframe
                        src="https://maps.google.com/maps?q=Piata+Libertatii+1,+Miercurea+Ciuc,+Romania&z=17&hl=hu&output=embed"
                        width="100%" height="100%"
                        style="border:0;"
                        allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

                <div class="bg-brand-700 text-white rounded-xl p-6">
                    <h3 class="font-bold mb-2">{{ __('contact.booking_title') }}</h3>
                    <p class="text-brand-100 text-sm mb-4">{{ __('contact.booking_desc') }}</p>
                    <a href="{{ route('booking') }}" class="w-full inline-flex items-center justify-center gap-2 bg-white text-brand-700 font-bold py-2.5 rounded-lg hover:bg-brand-50 transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ __('contact.booking_btn') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
