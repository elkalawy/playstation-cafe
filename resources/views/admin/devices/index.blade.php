<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('إدارة الأجهزة') }}
                        </h2>
                        <a href="{{ route('admin.devices.create') }}">
                            <x-primary-button>
                                {{ __('إضافة جهاز جديد') }}
                            </x-primary-button>
                        </a>
                    </div>
                    
                    <div class="relative overflow-x-auto border sm:rounded-lg">
                        <table class="w-full text-sm text-right text-gray-500">
                            {{-- تم تعديل رأس الجدول هنا --}}
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3">الاسم</th>
                                    <th scope="col" class="px-6 py-3">النوع</th>
                                    <th scope="col" class="px-6 py-3">الحالة</th>
                                    <th scope="col" class="px-6 py-3">تفاصيل الجلسة النشطة</th>
                                    <th scope="col" class="px-6 py-3">تحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($devices as $device)
                                    {{-- تم تعديل صفوف الجدول هنا لإضافة التباين --}}
                                    <tr class="bg-white border-b even:bg-gray-50 hover:bg-gray-100">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $device->name }}
                                        </th>
                                        <td class="px-6 py-4">{{ $device->type }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($device->status == 'available') bg-green-100 text-green-800 @endif
                                                @if($device->status == 'busy') bg-red-100 text-red-800 @endif
                                                @if($device->status == 'maintenance') bg-yellow-100 text-yellow-800 @endif
                                            ">
                                                {{ $device->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs">
                                            @if ($device->activeSession)
                                                <p>بواسطة: {{ $device->activeSession->user->name }}</p>
                                                <p>منذ: {{ $device->activeSession->start_time->diffForHumans() }}</p>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 flex items-center gap-2">
                                            <a href="{{ route('admin.devices.edit', $device->id) }}" class="font-medium text-blue-600 hover:underline">تعديل</a>
                                            <a href="{{ route('admin.devices.pricings.index', $device->id) }}" class="font-medium text-purple-600 hover:underline">الأسعار</a>
                                            <a href="{{ route('admin.devices.manageGames', $device->id) }}" class="font-medium text-indigo-600 hover:underline">الألعاب</a>
                                            <form action="{{ route('admin.devices.destroy', $device->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في الحذف؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:underline">حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b">
                                        <td colspan="5" class="px-6 py-4 text-center">لا توجد أجهزة لعرضها.</td>
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