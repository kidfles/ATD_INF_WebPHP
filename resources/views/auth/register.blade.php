<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="{ accountType: '{{ \App\Enums\UserRole::PrivateSeller->value }}' }">
        @csrf

        <div class="mt-4">
            <x-input-label for="role" :value="__('Ik wil mij registreren als:')" />
            <div class="mt-2 flex flex-col sm:flex-row gap-3">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="role" value="{{ \App\Enums\UserRole::User->value }}" x-model="accountType" class="rounded-full bg-slate-50 border-slate-200 text-emerald-500 shadow-sm focus:ring-emerald-400/30" checked>
                    <span class="ml-2 text-sm text-slate-600 font-medium">Koper / Huurder</span>
                </label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="role" value="{{ \App\Enums\UserRole::PrivateSeller->value }}" x-model="accountType" class="rounded-full bg-slate-50 border-slate-200 text-emerald-500 shadow-sm focus:ring-emerald-400/30">
                    <span class="ml-2 text-sm text-slate-600 font-medium">Particulier</span>
                </label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="role" value="{{ \App\Enums\UserRole::BusinessSeller->value }}" x-model="accountType" class="rounded-full bg-slate-50 border-slate-200 text-emerald-500 shadow-sm focus:ring-emerald-400/30">
                    <span class="ml-2 text-sm text-slate-600 font-medium">Zakelijk (Adverteerder)</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div x-show="accountType === '{{ \App\Enums\UserRole::BusinessSeller->value }}'" style="display: none;" class="mt-4 border-l-2 border-emerald-300 pl-4 bg-emerald-50/50 p-3 rounded-2xl">
            <h3 class="font-bold text-slate-700 mb-2">{{ __('Bedrijfsgegevens') }}</h3>
            
            <div class="mt-2">
                <x-input-label for="company_name" :value="__('Bedrijfsnaam')" />
                <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" :value="old('company_name')" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="kvk_number" :value="__('KVK Nummer')" />
                <x-text-input id="kvk_number" class="block mt-1 w-full" type="text" name="kvk_number" :value="old('kvk_number')" />
                <x-input-error :messages="$errors->get('kvk_number')" class="mt-2" />
            </div>
        </div>

        <!-- Name -->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="text-sm text-slate-400 hover:text-emerald-500 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-400/30 transition font-medium" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
