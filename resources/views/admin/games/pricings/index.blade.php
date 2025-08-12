<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تسعير اللعبة: {{ $game->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- ==================== بداية الجزء الجديد لعرض الصورة ==================== --}}
                    <div class="flex flex-col md:flex-row items-center md:items-start bg-gray-50 rounded-lg p-6 mb-8">
                        <div class="w-40 h-56 flex-shrink-0">
                            <img src="{{ $game->cover_image }}" alt="{{ $game->name }}" class="w-full h-full object-cover rounded-md shadow-lg">
                        </div>
                        <div class="mt-4 md:mt-0 md:mr-6 text-center md:text-right">
                            <h2 class="text-2xl font-bold text-gray-800">{{ $game->name }}</h2>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $game->is_game_based_playable ? 'هذه اللعبة تدعم نظام اللعب بالجيم.' : 'هذه اللعبة تدعم نظام اللعب بالوقت.' }}
                            </p>
                            <a href="{{ route('admin.games.index') }}" class="mt-4 inline-block text-sm text-indigo-600 hover:text-indigo-800">
                                &larr; العودة إلى قائمة الألعاب
                            </a>
                        </div>
                    </div>
                    {{-- ==================== نهاية الجزء الجديد لعرض الصورة ==================== --}}


                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">إضافة سعر جديد</h3>
                        <form action="{{ route('admin.games.pricings.store', $game) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            @csrf
                            <div>
                                <label for="controller_count" class="block text-sm font-medium text-gray-700">عدد الأجهزة</label>
                                <input type="number" name="controller_count" id="controller_count" value="1" min="1" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">السعر (بالجنيه)</label>
                                <input type="number" name="price" id="price" step="0.5" min="0" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="duration_in_minutes" class="block text-sm font-medium text-gray-700">المدة (بالدقائق)</label>
                                <input type="number" name="duration_in_minutes" id="duration_in_minutes" value="15" min="1" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700">إضافة</button>
                            </div>
                        </form>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 mb-4">الأسعار الحالية</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عدد الأجهزة</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">السعر</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المدة</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">حذف</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($game->pricings as $pricing)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pricing->controller_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($pricing->price, 2) }} جنيه</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pricing->duration_in_minutes }} دقيقة</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('admin.games.pricings.destroy', $pricing) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                            <p class="font-bold">لا توجد أسعار مضافة لهذه اللعبة بعد.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>