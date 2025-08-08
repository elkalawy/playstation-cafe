<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        {{ __('إضافة لعبة جديدة') }}
                    </h2>

                    <form method="POST" action="{{ route('admin.games.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('اسم اللعبة')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="is_game_based_playable" :value="__('هل تدعم اللعب بالجيم؟')" />
                            <select name="is_game_based_playable" id="is_game_based_playable" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="0">لا (لعب بالوقت فقط)</option>
                                <option value="1">نعم (يمكن لعبها بالجيم)</option>
                            </select>
                            <x-input-error :messages="$errors->get('is_game_based_playable')" class="mt-2" />
                        </div>
                        
                        <div class="mt-4">
                            <x-input-label for="cover_image" :value="__('صورة الغلاف (اختياري)')" />
                            <input id="cover_image" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="file" name="cover_image">
                            <x-input-error :messages="$errors->get('cover_image')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('حفظ اللعبة') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>