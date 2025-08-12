<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إدارة ألعاب الجهاز: {{ $device->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <form action="{{ route('admin.devices.syncGames', $device) }}" method="POST">
                        @csrf
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-900">اختر الألعاب المتاحة على هذا الجهاز</h3>
                            <p class="text-md text-gray-500 mt-1">اختر الكروت لحفظ التغييرات</p>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6" x-data="gameSelector()">
                            @forelse ($games as $game)
                                <label 
                                    class="relative rounded-lg overflow-hidden cursor-pointer group transition-all duration-300"
                                    :class="{ 'ring-4 ring-indigo-500 ring-offset-2': isSelected({{ $game->id }}) }"
                                >
                                    <input 
                                        type="checkbox" 
                                        name="games[]" 
                                        value="{{ $game->id }}"
                                        x-init="initSelection({{ $game->id }}, {{ in_array($game->id, $deviceGames ?? []) ? 'true' : 'false' }})"
                                        @change="toggleSelection({{ $game->id }})"
                                        {{-- ==================== هذا هو السطر الجديد الذي تم إضافته ==================== --}}
                                        :checked="isSelected({{ $game->id }})"
                                        class="absolute top-2 right-2 h-0 w-0 opacity-0"
                                    >
                                    
                                    <img src="{{ $game->cover_image ?: 'https://via.placeholder.com/300x400.png?text=No+Image' }}" 
                                         alt="{{ $game->name }}" 
                                         class="w-full h-auto object-cover aspect-[3/4] group-hover:scale-105 transition-transform duration-300">
                                    
                                    <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black to-transparent">
                                        <h4 class="text-white font-bold text-sm truncate">{{ $game->name }}</h4>
                                    </div>

                                    <div 
                                        x-show="isSelected({{ $game->id }})" 
                                        x-transition
                                        class="absolute inset-0 bg-indigo-700 bg-opacity-75 flex items-center justify-center"
                                        style="display: none;"
                                    >
                                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                </label>
                            @empty
                                <div class="col-span-full bg-white rounded-lg p-8 text-center text-gray-500">
                                    <p class="font-bold text-xl">{{ __('لا توجد ألعاب مضافة في النظام بعد.') }}</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-8 flex justify-start border-t pt-6">
                             <a href="{{ route('admin.devices.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                إلغاء
                            </a>
                            <button type="submit" class="mr-3 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function gameSelector() {
            return {
                selectedGames: [],
                initSelection(gameId, isSelected) {
                    if (isSelected && !this.selectedGames.includes(gameId)) {
                        this.selectedGames.push(gameId);
                    }
                },
                toggleSelection(gameId) {
                    const index = this.selectedGames.indexOf(gameId);
                    if (index > -1) {
                        this.selectedGames.splice(index, 1);
                    } else {
                        this.selectedGames.push(gameId);
                    }
                },
                isSelected(gameId) {
                    return this.selectedGames.includes(gameId);
                }
            }
        }
    </script>
</x-app-layout>