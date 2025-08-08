<x-app-layout>
    <div class="py-12" x-data="sessionManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        {{ __('لوحة تحكم الموظف') }}
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($devices as $device)
                            <div class="border rounded-lg shadow-md overflow-hidden @if($device->status == 'available') bg-green-50 border-green-200 @elseif($device->status == 'busy') bg-red-50 border-red-200 @else bg-yellow-50 border-yellow-200 @endif">
                                {{-- رأس الكارت --}}
                                <div class="p-4 flex justify-between items-center">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $device->name }} - <span class="text-sm font-normal">{{ $device->type }}</span></h3>
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full @if($device->status == 'available') bg-green-200 text-green-800 @endif @if($device->status == 'busy') bg-red-200 text-red-800 @endif @if($device->status == 'maintenance') bg-yellow-200 text-yellow-800 @endif">
                                        {{ $device->status == 'available' ? 'متاح' : ($device->status == 'busy' ? 'مشغول' : 'صيانة') }}
                                    </span>
                                </div>

                                {{-- محتوى الكارت --}}
                                <div class="p-4 bg-white border-t">
                                    @if ($device->activeSession)
                                        @php
                                            $session = $device->activeSession;
                                            $currentPeriod = $session->periods->whereNull('end_time')->first();
                                        @endphp
                                        <div class="space-y-3" x-data="timer(new Date('{{ $session->start_time->toISOString() }}'), {{ $session->total_cost ?? 0 }}, {{ optional($currentPeriod)->cost ?? 0 }}, '{{ optional($currentPeriod)->play_type }}', {{ $device->pricings->firstWhere('controller_count', optional($currentPeriod)->controller_count)->price_per_hour ?? 0 }}, new Date('{{ optional($currentPeriod)->start_time->toISOString() }}'))" x-init="startTimer()">
                                            <p><strong>الوقت الكلي:</strong> <span x-text="elapsedTime" class="font-bold text-lg"></span></p>
                                            <div class="p-3 bg-gray-50 rounded-md">
                                                <p class="text-sm font-bold mb-1">الفترة الحالية:</p>
                                                @if ($currentPeriod)
                                                    <p><strong>النوع:</strong> {{ $currentPeriod->play_type == 'time' ? 'وقت مفتوح' : 'لعبة' }}</p>
                                                    @if($currentPeriod->game)<p><strong>اللعبة:</strong> {{ $currentPeriod->game->name }}</p>@endif
                                                    <p><strong>الأجهزة:</strong> {{ $currentPeriod->controller_count }}</p>
                                                @endif
                                            </div>
                                            <p class="text-xl font-bold text-center text-green-500">التكلفة: <span x-text="formatCurrency(estimatedCost)"></span> جنيه</p>
                                        </div>
                                        <div class="mt-4 pt-4 border-t flex flex-col sm:flex-row gap-2">
                                            <x-secondary-button @click="openSwitchModal({{ $session->id }}, {{ $device->games }}, {{ $device->pricings }})">تبديل</x-secondary-button>
                                            <form action="{{ route('employee.play_sessions.end', $session->id) }}" method="POST" class="w-full">
                                                @csrf
                                                <x-danger-button type="submit" class="w-full justify-center" onclick="return confirm('هل أنت متأكد؟')">إنهاء</x-danger-button>
                                            </form>
                                        </div>
                                    @elseif($device->status == 'available')
                                        <div class="text-center">
                                            <p class="mb-4">الجهاز متاح لبدء جلسة جديدة.</p>
                                            <x-primary-button @click="openStartModal({{ $device->id }}, '{{ $device->name }}', {{ $device->games }}, {{ $device->pricings }})">بدء جلسة</x-primary-button>
                                        </div>
                                    @else
                                        <p>الجهاز في وضع الصيانة.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Modals (Start and Switch Session) --}}
        @include('employee.partials.modals')
    </div>

    {{-- JavaScript Logic --}}
    @include('employee.partials.scripts')
</x-app-layout>