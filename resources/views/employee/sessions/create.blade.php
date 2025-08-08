<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            بدء جلسة جديدة للجهاز: <span class="font-bold text-indigo-600">{{ $device->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" x-data="{ play_type: 'time' }">
                    
                    @if($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- تم تصحيح المسار هنا --}}
                    <form method="POST" action="{{ route('employee.play_sessions.start') }}">
                        @csrf
                        <input type="hidden" name="device_id" value="{{ $device->id }}">

                        <div class="mb-4">
                            <x-input-label :value="__('اختر نظام اللعب')" />
                            <div class="mt-2 flex items-center space-x-4 space-x-reverse">
                                <label class="flex items-center">
                                    <input type="radio" name="play_type" value="time" x-model="play_type" class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ms-2">وقت مفتوح</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="play_type" value="game" x-model="play_type" class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ms-2">لعبة (جيم)</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="controller_count" :value="__('عدد وحدات التحكم')" />
                            <select name="controller_count" id="controller_count" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500">
                                @foreach ($device->pricings as $pricing)
                                    <option value="{{ $pricing->controller_count }}">{{ $pricing->controller_count }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div x-show="play_type === 'game'" x-cloak class="mt-4">
                            <x-input-label for="game_id" :value="__('اختر اللعبة')" />
                            <select name="game_id" id="game_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500">
                                <option value="">-- اختر لعبة --</option>
                                @foreach ($device->games->where('is_game_based_playable', true) as $game)
                                    <option value="{{ $game->id }}">{{ $game->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('game_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('تأكيد وبدء الجلسة') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>