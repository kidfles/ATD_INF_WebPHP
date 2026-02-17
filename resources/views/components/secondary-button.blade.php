<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-5 py-2.5 bg-white/5 border border-white/10 rounded-xl font-semibold text-xs text-slate-300 uppercase tracking-widest shadow-sm hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-neon-violet/30 focus:ring-offset-2 focus:ring-offset-space-950 disabled:opacity-25 transition-all duration-200']) }}>
    {{ $slot }}
</button>
