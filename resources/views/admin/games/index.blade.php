<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة الألعاب') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6 flex justify-between items-center">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            قائمة الألعاب
                        </h2>
                        <a href="{{ route('admin.games.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            إضافة لعبة جديدة
                        </a>
                    </div>

                    {{-- ==================== هذا هو الجزء الذي تم تعديله ==================== --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        @forelse ($games as $game)
                            <div class="group relative rounded-lg overflow-hidden shadow-lg">
                                {{-- 1. تم تقليل ارتفاع حاوية الصورة --}}
                                <div class="h-80 w-full"> 
                                    <img src="{{ $game->cover_image }}" alt="{{ $game->name }}" class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-110">
                                </div>

                                <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black via-black/70 to-transparent">
                                    <h3 class="font-bold text-white text-md truncate">{{ $game->name }}</h3>
                                    <span @class([
                                        'px-2 py-0.5 text-xs font-semibold rounded-full',
                                        'bg-purple-200 text-purple-800' => $game->is_game_based_playable,
                                        'bg-gray-200 text-gray-800' => !$game->is_game_based_playable,
                                    ])>
                                        {{ $game->is_game_based_playable ? 'لعب بالجيم' : 'لعب بالوقت' }}
                                    </span>
                                </div>
                                
                                <div class="absolute inset-0 bg-black bg-opacity-70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="text-center space-y-2 w-2/3">
                                        <a href="{{ route('admin.games.pricings.index', $game) }}" class="block w-full text-center px-3 py-2 text-xs font-medium text-white bg-emerald-500 rounded-md hover:bg-emerald-600 transition-colors">الأسعار</a>
                                        <a href="{{ route('admin.games.edit', $game) }}" class="block w-full text-center px-3 py-2 text-xs font-medium text-white bg-sky-500 rounded-md hover:bg-sky-600 transition-colors">تعديل</a>
                                        <form action="{{ route('admin.games.destroy', $game) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-3 py-2 text-xs font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition-colors">حذف</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12 text-gray-500">
                                <p class="font-bold text-xl">لا توجد ألعاب مضافة بعد.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <div class="mt-8">
                        {{ $games->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>