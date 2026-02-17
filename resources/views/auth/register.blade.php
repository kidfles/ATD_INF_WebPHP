<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="{ accountType: 'private_ad' }">
        @csrf

        <div class="mt-4">
            <x-input-label for="role" :value="__('Ik wil mij registreren als:')" />
            <div class="mt-2 flex flex-col sm:flex-row gap-3">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="role" value="user" x-model="accountType" class="rounded-full bg-space-950 border-white/10 text-neon-violet shadow-sm focus:ring-neon-violet/30" checked>
                    <span class="ml-2 text-sm text-slate-400">Koper / Huurder</span>
                </label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="role" value="private_ad" x-model="accountType" class="rounded-full bg-space-950 border-white/10 text-neon-violet shadow-sm focus:ring-neon-violet/30">
                    <span class="ml-2 text-sm text-slate-400">Particulier</span>
                </label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="role" value="business_ad" x-model="accountType" class="rounded-full bg-space-950 border-white/10 text-neon-violet shadow-sm focus:ring-neon-violet/30">
                    <span class="ml-2 text-sm text-slate-400">Zakelijk (Adverteerder)</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div x-show="accountType === 'business_ad'" style="display: none;" class="mt-4 border-l-2 border-neon-violet/30 pl-4 bg-white/5 p-3 rounded-xl">
            <h3 class="font-bold text-white mb-2">{{ __('Bedrijfsgegevens') }}</h3>
            
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
            <a class="text-sm text-slate-400 hover:text-neon-cyan rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neon-violet/30 focus:ring-offset-space-950 transition" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
