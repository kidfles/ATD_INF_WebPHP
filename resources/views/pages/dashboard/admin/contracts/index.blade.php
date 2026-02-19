<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-extrabold text-slate-800 mb-6">{{ __('Contract Requests') }}</h2>
            
            @if(session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl mb-6">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 overflow-hidden">
                <div class="p-6">
                    @if($pendingProfiles->isEmpty())
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <p class="text-slate-400 italic">{{ __('No pending contract requests at the moment.') }}</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Company') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('KVK Number') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('User') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Contract') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($pendingProfiles as $profile)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="py-4 pr-4 font-bold text-slate-700">{{ $profile->company_name }}</td>
                                            <td class="py-4 pr-4 text-slate-500 font-mono text-xs">{{ $profile->kvk_number }}</td>
                                            <td class="py-4 pr-4">
                                                <div class="text-sm font-semibold text-slate-700">{{ $profile->user->name }}</div>
                                                <div class="text-xs text-slate-400 tracking-tight">{{ $profile->user->email }}</div>
                                            </td>
                                            <td class="py-4 pr-4">
                                                <a href="{{ route('dashboard.admin.contracts.download', $profile) }}" class="text-emerald-500 hover:text-emerald-600 font-bold text-sm flex items-center gap-1 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                    {{ __('Download PDF') }}
                                                </a>
                                            </td>
                                            <td class="py-4 flex gap-2">
                                                <form action="{{ route('dashboard.admin.contracts.approve', $profile) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-emerald-50 text-emerald-600 border border-emerald-100 font-bold py-1.5 px-4 rounded-full text-xs hover:bg-emerald-100 transition-all shadow-sm">{{ __('Approve') }}</button>
                                                </form>
                                                <form action="{{ route('dashboard.admin.contracts.decline', $profile) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to decline this contract?') }}');">
                                                    @csrf
                                                    <button type="submit" class="bg-red-50 text-red-500 border border-red-100 font-bold py-1.5 px-4 rounded-full text-xs hover:bg-red-100 transition-all shadow-sm">{{ __('Decline') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
