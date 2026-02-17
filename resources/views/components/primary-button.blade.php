<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-400 to-teal-500 border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-widest shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-emerald-100/50 active:translate-y-0 disabled:opacity-50 transition-all duration-300']) }}>
    {{ $slot }}
</button>
