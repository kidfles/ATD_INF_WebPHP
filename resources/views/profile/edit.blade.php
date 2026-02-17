<x-app-layout>
    {{--
        Pagina: Profiel Bewerken
        Doel: Hoofdpagina voor accountbeheer.
        Bevat: Inclusies voor profielinfo, wachtwoord wijzigen en account verwijderen.
    --}}
    <div class="py-4">
        <div class="max-w-4xl mx-auto space-y-6">

            {{-- Section 1: Profile Information --}}
            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-6 sm:p-8 opacity-0 animate-pop-in">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Section 2: Update Password --}}
            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-6 sm:p-8 opacity-0 animate-pop-in" style="animation-delay: 100ms;">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Section 3: Delete Account --}}
            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-6 sm:p-8 opacity-0 animate-pop-in" style="animation-delay: 200ms;">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
