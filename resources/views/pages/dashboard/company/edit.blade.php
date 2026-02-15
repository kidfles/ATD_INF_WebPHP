<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Company Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Branding & URL</h2>
                    <p class="mt-1 text-sm text-gray-600">Update your company colors and public URL.</p>
                </header>

                <form method="post" action="{{ route('dashboard.company.settings.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('patch')

                    <div class="space-y-2" x-data="{ color: '{{ $company->brand_color ?? '#000000' }}' }">
                        <x-input-label for="brand_color" value="Brand Color" />
                        <div class="flex items-center gap-4 mt-1">
                            <input type="color" name="brand_color" x-model="color" class="h-10 w-20 p-1 rounded border border-gray-300 cursor-pointer">
                            <x-text-input name="brand_color" type="text" class="block w-full uppercase" x-model="color" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="custom_url_slug" value="Public URL Slug" />
                        <div class="flex items-center mt-1">
                            <span class="text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md px-3 py-2">
                                {{ config('app.url') }}/company/
                            </span>
                            <x-text-input name="custom_url_slug" type="text" class="block w-full rounded-l-none" :value="old('custom_url_slug', $company->custom_url_slug)" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="kvk_number" value="KvK Number" />
                        <x-text-input name="kvk_number" type="text" class="block w-full mt-1" :value="old('kvk_number', $company->kvk_number)" />
                    </div>

                    <div class="flex items-center gap-4">
                         <x-primary-button>{{ __('Save Settings') }}</x-primary-button>
                         @if (session('status'))
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600"
                            >{{ session('status') }}</p>
                        @endif
                    </div>
                   
                </form>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Landing Page Builder</h2>
                        <p class="mt-1 text-sm text-gray-600">Manage sections on your public page.</p>
                    </div>
                    <form action="{{ route('dashboard.company.components.store') }}" method="POST" class="flex gap-2">
                        @csrf
                        <select name="type" class="border-gray-300 rounded-md text-sm">
                            <option value="hero">Add Hero Section</option>
                            <option value="text">Add Text Block</option>
                            <option value="featured_ads">Add Featured Ads</option>
                        </select>
                        <x-primary-button>Add</x-primary-button>
                    </form>
                </header>

                <form action="{{ route('dashboard.company.components.bulk') }}" method="POST" id="bulk-update-form">
                    @csrf
                    
                    <div id="sortable-components" class="space-y-4 mb-24">
                        @forelse($company->pageComponents as $component)
                            <div class="border rounded-lg p-4 bg-gray-50 transition transform hover:shadow-md relative group" data-id="{{ $component->id }}">
                                {{-- Hidden Input for Ordering --}}
                                <input type="hidden" name="ordered_ids[]" value="{{ $component->id }}">
                                
                                <div class="flex justify-between items-center mb-4">
                                    <div class="flex items-center gap-3">
                                        {{-- Drag Handle --}}
                                        <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600 p-1">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                        </div>
                                        <span class="badge bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs uppercase font-bold">
                                            {{ $component->component_type }}
                                        </span>
                                    </div>
                                    
                                    {{-- Remove Button --}}
                                    <button type="button" 
                                            onclick="if(confirm('Remove section?')) document.getElementById('delete-form-{{ $component->id }}').submit()" 
                                            class="text-red-500 text-sm hover:underline">
                                        Remove
                                    </button>
                                </div>

                                <div class="space-y-3">
                                    {{-- Content Inputs Keyed by Component ID --}}
                                    @if($component->component_type === 'hero')
                                        <input type="text" name="components[{{ $component->id }}][content][title]" value="{{ $component->content['title'] ?? '' }}" class="w-full border-gray-300 rounded text-sm" placeholder="Hero Title">
                                        <input type="text" name="components[{{ $component->id }}][content][subtitle]" value="{{ $component->content['subtitle'] ?? '' }}" class="w-full border-gray-300 rounded text-sm" placeholder="Subtitle">
                                    @elseif($component->component_type === 'text')
                                        <input type="text" name="components[{{ $component->id }}][content][heading]" value="{{ $component->content['heading'] ?? '' }}" class="w-full border-gray-300 rounded text-sm font-bold" placeholder="Heading">
                                        <textarea name="components[{{ $component->id }}][content][body]" rows="3" class="w-full border-gray-300 rounded text-sm">{{ $component->content['body'] ?? '' }}</textarea>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-4">No sections added yet.</p>
                        @endforelse
                    </div>

                    {{-- Sticky Footer Save Button --}}
                    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 flex justify-end z-50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
                         <div class="max-w-7xl mx-auto w-full flex justify-end px-4 sm:px-6 lg:px-8">
                            <x-primary-button class="py-3 px-6 text-base">
                                {{ __('Save All Changes') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>

                {{-- Hidden Delete Forms --}}
                @foreach($company->pageComponents as $component)
                    <form id="delete-form-{{ $component->id }}" action="{{ route('dashboard.company.components.destroy', $component) }}" method="POST" class="hidden">
                        @csrf @method('DELETE')
                    </form>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('sortable-components');
        var sortable = Sortable.create(el, {
            handle: '.drag-handle',
            animation: 150,
            // The order is now saved when the form is submitted, so we don't need onEnd fetch call.
        });
    });
</script>
