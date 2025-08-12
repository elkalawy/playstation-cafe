<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة الأجهزة') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('admin.devices.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            إضافة جهاز جديد
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse ($devices as $device)
                            <div @class([
                                'bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 border-l-4 flex flex-col', // Added flex flex-col
                                'border-green-500' => $device->status == 'available',
                                'border-blue-500' => $device->status == 'busy',
                                'border-yellow-500' => $device->status == 'maintenance',
                            ])>
                                <div class="p-5 flex-grow">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-800">{{ $device->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $device->type }}</p>
                                        </div>
                                        <div @class([
                                            'mt-1 flex items-center text-xs font-semibold px-2.5 py-1 rounded-full',
                                            'bg-green-100 text-green-800' => $device->status == 'available',
                                            'bg-blue-100 text-blue-800' => $device->status == 'busy',
                                            'bg-yellow-100 text-yellow-800' => $device->status == 'maintenance',
                                        ])>
                                            <span @class([
                                                'w-2 h-2 rounded-full mr-2',
                                                'bg-green-500' => $device->status == 'available',
                                                'bg-blue-500' => $device->status == 'busy',
                                                'bg-yellow-500' => $device->status == 'maintenance',
                                            ])></span>
                                            {{ $device->status == 'available' ? 'متاح' : ($device->status == 'busy' ? 'مشغول' : 'صيانة') }}
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 pt-4 border-t border-gray-200 text-sm">
                                        <h4 class="text-xs font-bold uppercase text-gray-400 mb-3">الحالة الحالية</h4>
                                        @if ($device->activeSession)
                                            <div class="space-y-3">
                                                <div class="flex items-center text-gray-600">
                                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <span>بدأت منذ: <strong>{{ $device->activeSession->session_start_at->diffForHumans() }}</strong></span>
                                                </div>
                                                <div class="flex items-center text-gray-600">
                                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                    <span>بواسطة: <strong>{{ optional($device->activeSession->user)->name ?: 'غير معروف' }}</strong></span>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-gray-500">لا توجد جلسة نشطة حاليًا.</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- ==================== بداية تصميم الأزرار الجديد ==================== --}}
                                <div class="bg-gray-50 p-3 grid grid-cols-2 gap-2 text-xs font-bold uppercase tracking-wider">
                                    <a href="{{ route('admin.devices.manageGames', $device->id) }}" class="text-center py-2 px-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">الألعاب</a>
                                    <a href="{{ route('admin.devices.pricings.index', $device->id) }}" class="text-center py-2 px-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">الأسعار</a>
                                    <a href="{{ route('admin.devices.edit', $device->id) }}" class="text-center py-2 px-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">تعديل</a>
                                    <form action="{{ route('admin.devices.destroy', $device->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الجهاز؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-center py-2 px-3 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">حذف</button>
                                    </form>
                                </div>
                                {{-- ==================== نهاية تصميم الأزرار الجديد ==================== --}}
                            </div>
                        @empty
                            <div class="md:col-span-2 lg:col-span-3 bg-white rounded-lg shadow p-8 text-center text-gray-500">
                                <p class="font-bold text-xl">{{ __('لا توجد أجهزة مضافة بعد.') }}</p>
                                <p class="mt-2">{{ __('قم بإضافة جهاز جديد لبدء الإدارة.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>