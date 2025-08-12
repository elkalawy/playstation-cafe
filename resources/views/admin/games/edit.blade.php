<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تعديل اللعبة: {{ $game->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.games.update', $game) }}" method="POST" enctype="multipart/form-data" x-data="imagePreview('{{ $game->cover_image }}')">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">اسم اللعبة</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $game->name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="is_game_based_playable" class="block text-sm font-medium text-gray-700">نوع اللعب</label>
                                    <select name="is_game_based_playable" id="is_game_based_playable" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="0" {{ $game->is_game_based_playable == 0 ? 'selected' : '' }}>لعب بالوقت</option>
                                        <option value="1" {{ $game->is_game_based_playable == 1 ? 'selected' : '' }}>لعب بالجيم</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="cover_image" class="block text-sm font-medium text-gray-700">تغيير غلاف اللعبة</label>
                                    <input type="file" name="cover_image" id="cover_image" @change="showPreview(event)" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>
                            </div>

                            <div class="flex items-center justify-center">
                                <div class="w-64 h-80 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                    <img :src="previewUrl" alt="معاينة الصورة" class="w-full h-full object-cover rounded-lg">
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-start border-t pt-6">
                            <a href="{{ route('admin.games.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">إلغاء</a>
                            <button type="submit" class="mr-3 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">حفظ التعديلات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function imagePreview(initialUrl = null) {
            return {
                previewUrl: initialUrl,
                showPreview(event) {
                    if (event.target.files.length > 0) {
                        this.previewUrl = URL.createObjectURL(event.target.files[0]);
                    }
                }
            }
        }
    </script>
</x-app-layout>