<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        {{ __('اختر الألعاب المتاحة للجهاز: ') . $device->name }}
                    </h2>
                    <form method="POST" action="{{ route('admin.devices.syncGames', $device->id) }}">
                        @csrf
                        @if($games->isEmpty())
                            <p class="col-span-full text-center py-12">لا توجد ألعاب لإضافتها. قم بإضافة ألعاب أولاً من صفحة إدارة الألعاب.</p>
                        @else
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                @foreach ($games as $game)
                                    <label class="relative group cursor-pointer">
                                        {{-- الكرت والصورة --}}
                                        <div class="aspect-w-3 aspect-h-4 bg-gray-200 rounded-lg overflow-hidden shadow-lg">
                                            @if ($game->cover_image)
                                                <img src="{{ Storage::url($game->cover_image) }}" alt="{{ $game->name }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                            @else
                                                <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-gray-500 text-sm">لا توجد صورة</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        {{-- اسم اللعبة في الأسفل --}}
                                        <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/80 to-transparent">
                                            <h3 class="text-white text-sm font-bold truncate">{{ $game->name }}</h3>
                                        </div>

                                        {{-- Checkbox المخفي --}}
                                        <input type="checkbox" name="games[]" value="{{ $game->id }}"
                                               @if(in_array($game->id, $assignedGames)) checked @endif
                                               class="hidden peer">
                                        
                                        {{-- علامة الصح التي تظهر عند الاختيار --}}
                                        <div class="absolute top-2 right-2 w-8 h-8 bg-white/50 backdrop-blur-sm rounded-full flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity duration-300">
                                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>

                                        {{-- حدود الكرت عند الاختيار --}}
                                        <div class="absolute inset-0 rounded-lg ring-4 ring-green-500 ring-offset-2 opacity-0 peer-checked:opacity-100 transition-opacity duration-300"></div>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>{{ __('حفظ التغييرات') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>