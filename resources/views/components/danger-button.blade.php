<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-red-50 border border-red-200 rounded-full font-bold text-sm text-red-600 uppercase tracking-widest hover:bg-red-100 hover:text-red-700 focus:outline-none focus:ring-4 focus:ring-red-100/50 active:bg-red-200 disabled:opacity-50 transition-all duration-300']) }}>
    {{ $slot }}
</button>
