<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-red-600/80 border border-red-500/30 rounded-xl font-semibold text-xs text-white uppercase tracking-widest shadow-[0_0_15px_rgba(239,68,68,0.3)] hover:bg-red-500 hover:shadow-[0_0_25px_rgba(239,68,68,0.5)] focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-space-950 active:bg-red-700 transition-all duration-200']) }}>
    {{ $slot }}
</button>
