<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center mb-6 space-x-6 space-x-reverse">
                        {{-- تم تغيير الأيقونة هنا --}}
                        <div class="p-3 bg-gray-100 rounded-md shrink-0">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                {{ __('تسعير الجهاز: ') }} <span class="text-indigo-600">{{ $device->name }}</span>
                            </h2>
                            <p class="text-sm text-gray-500">{{ $device->type }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        {{-- العمود الأول: فورم إضافة سعر جديد --}}
                        <div class="md:col-span-1">
                            <form method="POST" action="{{ route('admin.devices.pricings.store', $device) }}" class="space-y-4 p-4 border rounded-lg bg-gray-50">
                                @csrf
                                <h3 class="font-semibold text-lg text-center mb-2">إضافة سعر جديد</h3>
                                
                                <div>
                                    <x-input-label for="controller_count" :value="__('عدد أجهزة التحكم')" />
                                    <x-text-input id="controller_count" type="number" name="controller_count" :value="old('controller_count')" required class="mt-1 block w-full" />
                                    <x-input-error :messages="$errors->get('controller_count')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="price_per_hour" :value="__('السعر / ساعة (بالجنيه)')" />
                                    <x-text-input id="price_per_hour" type="number" name="price_per_hour" step="0.5" :value="old('price_per_hour')" required class="mt-1 block w-full" />
                                    <x-input-error :messages="$errors->get('price_per_hour')" class="mt-2" />
                                </div>
                                
                                <div class="pt-2">
                                    <x-primary-button class="w-full justify-center text-sm">
                                        {{ __('إضافة السعر') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>

                        {{-- العمود الثاني: عرض الأسعار الحالية --}}
                        <div class="md:col-span-2">
                            <h3 class="font-semibold text-lg text-gray-800 mb-2">{{ __('الأسعار الحالية') }}</h3>
                            <div class="space-y-3">
                                @forelse($pricings as $pricing)
                                    <div class="flex justify-between items-center bg-white p-3 border border-gray-200 rounded-lg shadow-sm hover:border-indigo-400 transition">
                                        <div class="flex items-center space-x-4 space-x-reverse">
                                            <div class="text-sm font-bold text-indigo-600 bg-indigo-100 rounded-full w-10 h-10 flex items-center justify-center shrink-0">{{ $pricing->controller_count }}</div>
                                            <div>
                                                <p class="font-semibold text-gray-700">{{ number_format($pricing->price_per_hour, 2) }} جنيه / ساعة</p>
                                                <p class="text-xs text-gray-500">لعدد {{ $pricing->controller_count }} أجهزة تحكم</p>
                                            </div>
                                        </div>
                                        <div>
                                            <form action="{{ route('admin.devices.pricings.destroy', $pricing) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا السعر؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-2 rounded-full">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-10 bg-gray-50 rounded-lg">
                                        <p class="text-gray-500">لا توجد أسعار معرفة لهذا الجهاز بعد.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>