@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-slate-50 border-transparent text-slate-700 placeholder-slate-400 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 rounded-2xl py-3 px-4 shadow-sm transition-all duration-200']) }}>
