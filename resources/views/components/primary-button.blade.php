<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-neon-violet to-neon-cyan border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest shadow-[0_0_15px_rgba(139,92,246,0.4)] hover:shadow-[0_0_25px_rgba(139,92,246,0.6)] hover:scale-105 focus:outline-none focus:ring-2 focus:ring-neon-violet focus:ring-offset-2 focus:ring-offset-space-950 active:scale-95 transition-all duration-200']) }}>
    {{ $slot }}
</button>
