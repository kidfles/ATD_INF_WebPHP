@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-space-950 border-white/10 text-white placeholder-slate-500 focus:border-neon-violet focus:ring-neon-violet/20 rounded-xl shadow-sm']) }}>
