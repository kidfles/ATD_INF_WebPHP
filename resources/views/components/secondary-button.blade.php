<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-3 bg-white border-2 border-slate-100 rounded-full font-bold text-sm text-slate-700 uppercase tracking-widest hover:border-emerald-200 hover:text-emerald-600 focus:outline-none focus:ring-4 focus:ring-emerald-100/50 disabled:opacity-50 transition-all duration-300']) }}>
    {{ $slot }}
</button>
