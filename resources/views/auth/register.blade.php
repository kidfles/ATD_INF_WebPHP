<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="{ accountType: 'private_ad' }">
        @csrf

        <div class="mt-4">
            <x-input-label for="role" :value="__('Ik wil mij registreren als:')" />
            <div class="mt-1 flex gap-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="role" value="private_ad" x-model="accountType" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-600">Particulier</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="role" value="business_ad" x-model="accountType" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-600">Zakelijk (Adverteerder)</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div x-show="accountType === 'business_ad'" style="display: none;" class="mt-4 border-l-4 border-indigo-400 pl-4 bg-gray-50 p-2 rounded">
            <h3 class="font-bold text-gray-700 mb-2">Bedrijfsgegevens</h3>
            
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
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
