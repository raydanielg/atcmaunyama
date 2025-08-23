@php
    /** @var \Illuminate\Support\Collection|array $classes */
    $palette = [
        ['bg' => 'from-indigo-50 to-white', 'icon' => 'text-indigo-600', 'border' => 'border-indigo-100'],
        ['bg' => 'from-emerald-50 to-white', 'icon' => 'text-emerald-600', 'border' => 'border-emerald-100'],
        ['bg' => 'from-amber-50 to-white', 'icon' => 'text-amber-600', 'border' => 'border-amber-100'],
        ['bg' => 'from-rose-50 to-white', 'icon' => 'text-rose-600', 'border' => 'border-rose-100'],
        ['bg' => 'from-sky-50 to-white', 'icon' => 'text-sky-600', 'border' => 'border-sky-100'],
        ['bg' => 'from-violet-50 to-white', 'icon' => 'text-violet-600', 'border' => 'border-violet-100'],
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Classes') }}</h2>
            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 px-3 py-1.5 border rounded-md hover:bg-gray-50">
                <span class="material-symbols-outlined text-gray-600">arrow_back</span>
                <span class="text-sm">Back to Dashboard</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <!-- Intro strip -->
            <div class="h-1 bg-gray-100 rounded"></div>
            <div class="border-t border-dashed border-gray-300"></div>

            <!-- Grid of class cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse($classes as $idx => $c)
                    @php $theme = $palette[$idx % count($palette)]; @endphp
                    <div class="group rounded-xl border {{ $theme['border'] }} bg-gradient-to-b {{ $theme['bg'] }} p-4 shadow-sm hover:shadow transition">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined {{ $theme['icon'] }}">menu_book</span>
                                <div class="font-semibold text-gray-800">{{ $c->name }}</div>
                            </div>
                        </div>
                        @if(!empty($c->description))
                            <div class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $c->description }}</div>
                        @endif
                        <div class="mt-3 flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2 text-gray-600">
                                <span class="material-symbols-outlined text-[18px] {{ $theme['icon'] }}">category</span>
                                <span>{{ method_exists($c,'subjects') ? ($c->subjects->count() ?? 0) : 0 }} Subjects</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <span class="material-symbols-outlined text-[18px] {{ $theme['icon'] }}">event_note</span>
                                <span>Materials ready</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('user.classes.show', $c->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md border bg-white hover:bg-gray-50 text-sm">
                                <span class="material-symbols-outlined text-[18px] {{ $theme['icon'] }}">visibility</span>
                                View
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="p-6 text-center rounded-xl border border-gray-200 bg-white">
                            <div class="text-gray-700 font-medium">No classes found</div>
                            <div class="text-sm text-gray-500">Please check back later.</div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
