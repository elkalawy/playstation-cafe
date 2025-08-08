<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        تعديل الجلسة النشطة للجهاز: <span class="font-bold text-indigo-600">{{ $device->name }}</span>
                    </h2>

                    <div class="border-t pt-4">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">تغيير عدد وحدات التحكم</h3>
                        <form method="POST" action="{{ route('employee.sessions.changeControllers', $session) }}">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                <div>
                                    <x-input-label for="controller_count" :value="__('اختر العدد الجديد')" />
                                    <select name="controller_count" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">-- اختر --</option>
                                        @foreach($timeBasedPricings as $pricing)
                                            <option value="{{ $pricing->controller_count }}" @if($session->currentPeriod->controller_count == $pricing->controller_count) disabled @endif>
                                                {{ $pricing->controller_count }} يد ({{$pricing->price_per_hour}} جنيه/ساعة)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-primary-button>
                                        {{ __('تحديث اليدات') }}
                                    </x-primary-button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if ($session->currentPeriod->play_type === 'time')
                        <div class="mt-6 border-t pt-4">
                            <h3 class="font-semibold text-lg text-gray-800 mb-2">التحويل إلى وقت محدد</h3>
                            <form method="POST" action="{{ route('employee.sessions.switchToFixedTime', $session) }}">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                    <div>
                                        <x-input-label for="duration_minutes" :value="__('أدخل المدة الجديدة بالدقائق')" />
                                        <x-text-input id="duration_minutes" class="block mt-1 w-full" type="number" name="duration_minutes" required min="1" />
                                    </div>
                                    <div>
                                        <x-primary-button>
                                            {{ __('تحويل') }}
                                        </x-primary-button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>