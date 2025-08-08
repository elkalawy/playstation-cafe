<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        {{ __('تعديل الجهاز: ') . $device->name }}
                    </h2>
                    <form method="POST" action="{{ route('admin.devices.update', $device->id) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <x-input-label for="name" :value="__('اسم الجهاز')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $device->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="type" :value="__('نوع الجهاز (e.g., PS5)')" />
                            <x-text-input id="type" class="block mt-1 w-full" type="text" name="type" :value="old('type', $device->type)" required />
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('حالة الجهاز')" />
                            <select name="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="available" @selected(old('status', $device->status) == 'available')>متاح</option>
                                <option value="busy" @selected(old('status', $device->status) == 'busy')>مشغول</option>
                                <option value="maintenance" @selected(old('status', $device->status) == 'maintenance')>صيانة</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
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