<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        {{ __('تعديل اللعبة: ') . $game->name }}
                    </h2>
                    <form method="POST" action="{{ route('admin.games.update', $game->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <x-input-label for="name" :value="__('اسم اللعبة')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $game->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="cover_image" :value="__('تغيير غلاف اللعبة (اختياري)')" />
                            <x-text-input id="cover_image" class="block mt-1 w-full" type="file" name="cover_image" />
                            <x-input-error :messages="$errors->get('cover_image')" class="mt-2" />
                            @if ($game->cover_image)
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">الغلاف الحالي:</p>
                                <img src="{{ Storage::url($game->cover_image) }}" alt="{{ $game->name }}" class="w-24 h-24 object-cover rounded mt-1">
                            </div>
                            @endif
                        </div>

                        <div class="mt-4">
                            <x-input-label for="is_game_based_playable" :value="__('هل يمكن لعبها بنظام المباراة؟')" />
                            <select name="is_game_based_playable" id="is_game_based_playable" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="1" @selected(old('is_game_based_playable', $game->is_game_based_playable) == 1)>نعم</option>
                                <option value="0" @selected(old('is_game_based_playable', $game->is_game_based_playable) == 0)>لا</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>{{ __('حفظ التعديلات') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>