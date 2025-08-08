<div x-show="startModalOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div @click.away="startModalOpen = false" class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
        <h3 class="text-lg font-bold mb-4" x-text="`بدء جلسة على: ${modalDeviceName}`"></h3>
        <form :action="'{{ route('employee.play_sessions.start') }}'" method="POST">
            @csrf
            <input type="hidden" name="device_id" :value="modalDeviceId">
            <div class="space-y-4">
                <div>
                    <x-input-label for="play_type" value="نوع اللعب" />
                    <select name="play_type" x-model="playType" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="time">وقت</option>
                        <option value="game">لعبة (جيم)</option>
                    </select>
                </div>
                <div x-show="playType === 'time'" class="space-y-4 border-t pt-4">
                    <div>
                        <x-input-label for="time_type" value="نظام الوقت" />
                        <select name="time_type" x-model="timeType" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="open">وقت مفتوح</option>
                            <option value="fixed">وقت محدد</option>
                        </select>
                    </div>
                    <div x-show="timeType === 'fixed'">
                        <x-input-label for="duration_in_minutes" value="أدخل المدة بالدقائق (مثال: 60)" />
                        <x-text-input type="number" name="duration_in_minutes" class="mt-1 block w-full" step="15" min="15" />
                    </div>
                </div>
                <div x-show="playType === 'game'">
                    <x-input-label for="game_id" value="اختر اللعبة" />
                    <select name="game_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <template x-for="game in modalGames" :key="game.id"><option :value="game.id" x-text="game.name"></option></template>
                    </select>
                </div>
                <div>
                    <x-input-label for="controller_count" value="عدد أجهزة التحكم" />
                     <select name="controller_count" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <template x-for="pricing in modalPricings" :key="pricing.id"><option :value="pricing.controller_count" x-text="pricing.controller_count"></option></template>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-4">
                <x-secondary-button @click="startModalOpen = false">إلغاء</x-secondary-button>
                <x-primary-button type="submit">بدء</x-primary-button>
            </div>
        </form>
    </div>
</div>

<div x-show="switchModalOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div @click.away="switchModalOpen = false" class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
        <h3 class="text-lg font-bold mb-4">تبديل نوع اللعب</h3>
        <form :action="`/employee/play-sessions/${modalSessionId}/switch`" method="POST">
            @csrf
            <div class="space-y-4">
                 <div>
                    <x-input-label for="switch_play_type" value="النوع الجديد" />
                    <select name="play_type" x-model="playType" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="time">وقت مفتوح</option>
                        <option value="game">لعبة (جيم)</option>
                    </select>
                </div>
                <div x-show="playType === 'game'">
                    <x-input-label for="switch_game_id" value="اختر اللعبة" />
                    <select name="game_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <template x-for="game in modalGames" :key="game.id"><option :value="game.id" x-text="game.name"></option></template>
                    </select>
                </div>
                <div>
                    <x-input-label for="switch_controller_count" value="عدد أجهزة التحكم" />
                     <select name="controller_count" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <template x-for="pricing in modalPricings" :key="pricing.id"><option :value="pricing.controller_count" x-text="pricing.controller_count"></option></template>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-4">
                <x-secondary-button @click="switchModalOpen = false">إلغاء</x-secondary-button>
                <x-primary-button type="submit">تبديل</x-primary-button>
            </div>
        </form>
    </div>
</div>