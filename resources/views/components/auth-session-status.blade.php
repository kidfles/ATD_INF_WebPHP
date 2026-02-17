@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3']) }}>
        {{ $status }}
    </div>
@endif
