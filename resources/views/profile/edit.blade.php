<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-white leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-4xl mx-auto space-y-6">

            {{-- Section 1: Profile Information --}}
            <div class="bg-space-900/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-6 sm:p-8 animate-fade-in-up">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Section 2: Update Password --}}
            <div class="bg-space-900/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-6 sm:p-8 animate-fade-in-up" style="animation-delay: 100ms;">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Section 3: Delete Account --}}
            <div class="bg-space-900/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-6 sm:p-8 animate-fade-in-up" style="animation-delay: 200ms;">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
