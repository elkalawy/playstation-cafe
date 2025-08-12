<div x-show="startModalOpen" style="display: none;" @keydown.escape.window="startModalOpen = false" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="startModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="startModalOpen = false" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div x-show="startModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('employee.play_sessions.start') }}" method="POST">
                @csrf
                <input type="hidden" name="device_id" :value="modalDeviceId">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">بدء جلسة جديدة لـ <span x-text="modalDeviceName" class="font-bold"></span></h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="font-bold">نوع اللعب:</label>
                            <div class="mt-2 flex rounded-md shadow-sm">
                                <button type="button" @click="playType = 'time'" :class="{'bg-indigo-600 text-white': playType === 'time', 'bg-white text-gray-700 hover:bg-gray-50': playType !== 'time'}" class="flex-1 px-4 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">وقت مفتوح</button>
                                <button type="button" @click="playType = 'game'" :class="{'bg-indigo-600 text-white': playType === 'game', 'bg-white text-gray-700 hover:bg-gray-50': playType !== 'game'}" class="flex-1 -ml-px px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">لعبة (جيم)</button>
                            </div>
                            <input type="hidden" name="play_type" x-model="playType">
                        </div>

                        <div x-show="playType === 'game'" x-transition>
                            <label for="game_id" class="block text-sm font-medium text-gray-700">اختر اللعبة:</label>
                            <select name="game_id" id="game_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <template x-for="game in modalGames" :key="game.id">
                                    <option :value="game.id" x-text="game.name"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label for="controller_count" class="block text-sm font-medium text-gray-700">عدد أذرع التحكم:</label>
                            <input type="number" name="controller_count" id="controller_count" value="1" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">بدء الجلسة</button>
                    <button type="button" @click="startModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="switchModalOpen" style="display: none;" @keydown.escape.window="switchModalOpen = false" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="switchModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="switchModalOpen = false" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div x-show="switchModalOpen" x-transition class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form :action="switchUrlTemplate.replace('ID_PLACEHOLDER', modalSessionId)" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">تبديل نوع اللعب</h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="font-bold">نوع اللعب الجديد:</label>
                            <div class="mt-2 flex rounded-md shadow-sm">
                                <button type="button" @click="playType = 'time'" :class="{'bg-indigo-600 text-white': playType === 'time', 'bg-white text-gray-700 hover:bg-gray-50': playType !== 'time'}" class="flex-1 px-4 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">وقت مفتوح</button>
                                <button type="button" @click="playType = 'game'" :class="{'bg-indigo-600 text-white': playType === 'game', 'bg-white text-gray-700 hover:bg-gray-50': playType !== 'game'}" class="flex-1 -ml-px px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">لعبة (جيم)</button>
                            </div>
                            <input type="hidden" name="play_type" x-model="playType">
                        </div>
                        <div x-show="playType === 'game'" x-transition>
                             <label for="switch_game_id" class="block text-sm font-medium text-gray-700">اختر اللعبة:</label>
                             <select name="game_id" id="switch_game_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                 <template x-for="game in modalGames" :key="game.id">
                                     <option :value="game.id" x-text="game.name"></option>
                                 </template>
                             </select>
                        </div>
                        <div>
                             <label for="switch_controller_count" class="block text-sm font-medium text-gray-700">عدد أذرع التحكم:</label>
                             <input type="number" name="controller_count" id="switch_controller_count" value="1" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">تبديل</button>
                    <button type="button" @click="switchModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="alertModalOpen" style="display: none;" @keydown.escape.window="alertModalOpen = false" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="alertModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="alertModalOpen = false" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div x-show="alertModalOpen" x-transition class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xs sm:w-full">
            <form :action="alertUrlTemplate.replace('ID_PLACEHOLDER', alertSessionId)" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">ضبط منبه</h3>
                    <div class="mt-4">
                        <label for="alert_in_minutes" class="block text-sm font-medium text-gray-700">نبهني بعد (بالدقائق):</label>
                        <input type="number" name="alert_in_minutes" id="alert_in_minutes" value="10" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-black hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-600 sm:ml-3 sm:w-auto sm:text-sm">ضبط</button>
                    <button type="button" @click="alertModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="gameModalOpen" style="display: none;" @keydown.escape.window="gameModalOpen = false" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="gameModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="gameModalOpen = false" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div x-show="gameModalOpen" x-transition class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">الألعاب المتوفرة على <span x-text="modalDeviceNameForGames" class="font-bold"></span></h3>
                <div class="mt-4 max-h-[60vh] overflow-y-auto pr-2">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <template x-for="game in modalGamesList" :key="game.id">
                            <div class="rounded-md overflow-hidden border">
                                <img :src="game.cover_image" :alt="game.name" class="w-full h-auto object-cover aspect-[3/4]">
                                <p class="text-sm font-semibold p-2 text-center truncate" x-text="game.name"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="gameModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<div x-show="pricingModalOpen" style="display: none;" @keydown.escape.window="pricingModalOpen = false" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="pricingModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="pricingModalOpen = false" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div x-show="pricingModalOpen" x-transition class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">قائمة أسعار <span x-text="modalDeviceNameForPricing" class="font-bold"></span></h3>
                <div class="mt-4 max-h-[60vh] overflow-y-auto pr-2 space-y-6">
                    <div>
                        <h4 class="font-bold text-md text-gray-700 mb-2">أسعار اللعب بالوقت</h4>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عدد الأجهزة</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">السعر/ساعة</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-if="modalPricingsList.length === 0">
                                    <tr><td colspan="2" class="px-6 py-4 text-center text-gray-500">لا توجد أسعار مضافة لهذا الجهاز.</td></tr>
                                </template>
                                <template x-for="pricing in modalPricingsList" :key="pricing.id">
                                    <tr>
                                        <td class="px-6 py-4" x-text="pricing.controller_count"></td>
                                        <td class="px-6 py-4" x-text="`${pricing.price_per_hour} جنيه`"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-md text-gray-700 mb-2">أسعار الألعاب (بالجيم)</h4>
                        <div class="space-y-4">
                             <div>
                                <label for="game_pricing_filter" class="block text-sm font-medium text-gray-700">اختر لعبة لعرض أسعارها:</label>
                                <select id="game_pricing_filter" x-model="selectedGameId" @change="updatePricingView()" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">-- اختر لعبة --</option>
                                    <template x-for="game in modalGamesForPricing.filter(g => g.pricings.length > 0)" :key="game.id">
                                        <option :value="game.id" x-text="game.name"></option>
                                    </template>
                                </select>
                            </div>

                            <table class="min-w-full divide-y divide-gray-200" x-show="selectedGameId">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عدد الأجهزة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">السعر</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المدة</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                     <template x-if="selectedGamePricings.length === 0 && selectedGameId">
                                        <tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">لا توجد أسعار لهذه اللعبة.</td></tr>
                                    </template>
                                    <template x-for="pricing in selectedGamePricings" :key="pricing.id">
                                        <tr>
                                            <td class="px-6 py-4" x-text="pricing.controller_count"></td>
                                            <td class="px-6 py-4" x-text="`${pricing.price} جنيه`"></td>
                                            <td class="px-6 py-4" x-text="`${pricing.duration_in_minutes} دقيقة`"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="pricingModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">إغلاق</button>
            </div>
        </div>
    </div>
</div>