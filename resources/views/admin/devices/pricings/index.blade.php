<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        {{ __('تسعير الجهاز: ') . $device->name }}
                    </h2>
                    
                    {{-- فورم إضافة سعر جديد --}}
                    <form method="POST" action="{{ route('admin.devices.pricings.store', $device) }}" class="mb-6 pb-6 border-b">
                        @csrf
                        <h3 class="font-semibold text-lg mb-2">إضافة سعر جديد</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="controller_count" :value="__('عدد أجهزة التحكم')" />
                                <x-text-input id="controller_count" type="number" name="controller_count" :value="old('controller_count')" required class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('controller_count')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="price_per_hour" :value="__('السعر / ساعة')" />
                                <x-text-input id="price_per_hour" type="number" name="price_per_hour" step="0.01" :value="old('price_per_hour')" required class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('price_per_hour')" class="mt-2" />
                            </div>
                            <div class="self-end">
                                <x-primary-button class="w-full justify-center">{{ __('إضافة السعر') }}</x-primary-button>
                            </div>
                        </div>
                    </form>

                    {{-- جدول الأسعار الحالية --}}
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">{{ __('الأسعار الحالية') }}</h3>
                    <div class="relative overflow-x-auto border sm:rounded-lg">
                        <table class="w-full text-sm text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3">عدد أجهزة التحكم</th>
                                    <th scope="col" class="px-6 py-3">السعر / ساعة</th>
                                    <th scope="col" class="px-6 py-3">تحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pricings as $pricing)
                                <tr class="bg-white border-b even:bg-gray-50 hover:bg-gray-100">
                                    <td class="px-6 py-4">{{ $pricing->controller_count }}</td>
                                    <td class="px-6 py-4">{{ number_format($pricing->price_per_hour, 2) }} جنيه</td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.devices.pricings.destroy', $pricing) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:underline">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-white border-b">
                                    <td colspan="3" class="px-6 py-4 text-center">لا توجد أسعار معرفة لهذا الجهاز.</td>
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