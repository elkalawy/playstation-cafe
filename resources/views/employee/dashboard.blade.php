<x-app-layout>
    <style>
        .blinking-border {
            animation: blinker 1.5s linear infinite;
        }
        @keyframes blinker {
            50% {
                border-color: #ef4444; /* red-500 */
                box-shadow: 0 0 15px rgba(239, 68, 68, 0.5);
            }
        }
    </style>
    
    <div class="py-12" x-data="sessionManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4">
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md" role="alert">
                        <p class="font-bold">نجاح</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-md" role="alert">
                        <p class="font-bold">خطأ</p>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        {{ __('لوحة تحكم الموظف') }}
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($devices as $device)
                            @php
                                $session = $device->activeSession;
                                $currentPeriod = $session ? $session->periods->whereNull('end_time')->first() : null;
                                $costOfPreviousPeriods = $session ? $session->periods->whereNotNull('end_time')->sum('cost') : 0;
                            @endphp
                            
                            <div 
                                @if($session)
                                    x-data="timer({
                                        startTime: '{{ $session->session_start_at->toISOString() }}',
                                        alertTime: '{{ optional($session->alert_at)->toISOString() }}',
                                        costOfPreviousPeriods: {{ $costOfPreviousPeriods }},
                                        periodCost: {{ optional($currentPeriod)->cost ?? 0 }},
                                        periodType: '{{ optional($currentPeriod)->play_type }}',
                                        periodStartTime: '{{ optional($currentPeriod)->start_time?->toISOString() }}',
                                        pricePerHour: {{ optional(optional($device->pricings)->firstWhere('controller_count', optional($currentPeriod)->controller_count))->price_per_hour ?? 0 }}
                                    })"
                                    x-init="init()"
                                @else
                                    x-data="{ isTimeUp: false }"
                                @endif
                                :class="{ 'blinking-border !bg-red-50 !border-red-200': isTimeUp }"
                                @class([
                                    'border rounded-lg shadow-md overflow-hidden transition-all',
                                    'bg-green-50 border-green-200' => $device->status == 'available',
                                    'bg-blue-50 border-blue-200' => $device->status == 'busy',
                                    'bg-yellow-50 border-yellow-200' => $device->status == 'maintenance',
                                ])>
                                
                                <div class="p-4 flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $device->name }} - <span class="text-sm font-normal">{{ $device->type }}</span></h3>
                                        <div class="mt-2 flex items-center space-x-2 rtl:space-x-reverse">
                                            @if($device->games->isNotEmpty())
                                                <button @click="openGameModal('{{ $device->name }}', {{ $device->games }})" class="flex items-center -space-x-2 rtl:space-x-reverse cursor-pointer group">
                                                    @foreach($device->games->take(4) as $game)
                                                        <img class="h-8 w-8 rounded-full object-cover border-2 border-white group-hover:border-indigo-300 transition-colors" src="{{ $game->cover_image }}" alt="{{ $game->name }}" title="{{ $game->name }}">
                                                    @endforeach
                                                    @if($device->games->count() > 4)
                                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 border-2 border-white group-hover:border-indigo-300 transition-colors">
                                                            +{{ $device->games->count() - 4 }}
                                                        </div>
                                                    @endif
                                                </button>
                                            @endif
                                            
                                            @if($device->pricings->isNotEmpty() || $device->games->contains(fn($game) => $game->pricings->isNotEmpty()))
                                                <button @click="openPricingModal('{{ $device->name }}', {{ $device->pricings }}, {{ $device->games }})" class="h-8 w-8 rounded-full bg-green-200 text-green-800 flex items-center justify-center text-lg font-bold border-2 border-white hover:bg-green-300 transition-colors" title="عرض الأسعار">
                                                    $
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <span 
                                        :class="{ '!bg-red-200 !text-red-800': isTimeUp }"
                                        @class([
                                            'px-3 py-1 text-sm font-semibold rounded-full',
                                            'bg-green-200 text-green-800' => $device->status == 'available',
                                            'bg-blue-200 text-blue-800' => $device->status == 'busy',
                                            'bg-yellow-200 text-yellow-800' => $device->status == 'maintenance',
                                        ])>
                                        <span x-show="isTimeUp">انتهى الوقت</span>
                                        <span x-show="!isTimeUp">{{ $device->status == 'available' ? 'متاح' : ($device->status == 'busy' ? 'مشغول' : 'صيانة') }}</span>
                                    </span>
                                </div>

                                <div class="p-4 bg-white border-t">
                                    @if ($session)
                                        <div class="space-y-3">
                                            <div class="flex justify-between items-center">
                                                <span><strong>الوقت الكلي:</strong></span>
                                                <span x-text="elapsedTime" class="font-bold text-lg text-gray-800"></span>
                                            </div>
                                            
                                            <div x-show="alertTime" x-cloak class="flex justify-between items-center text-sm bg-yellow-100 p-2 rounded-md">
                                                <span><strong>المنبه المتبقي:</strong></span>
                                                <span x-text="alertCountdown" class="font-bold text-yellow-800"></span>
                                            </div>

                                            <div class="mt-3 pt-3 border-t border-gray-200">
                                                <p class="text-sm font-bold mb-2 text-gray-600">سجل الجلسة:</p>
                                                <ul class="space-y-2 text-xs text-gray-500">
                                                    @foreach ($session->periods->sortBy('start_time') as $period)
                                                        <li class="flex justify-between items-center p-2 rounded-md {{ $loop->last ? 'bg-blue-100 text-blue-800 font-semibold' : 'bg-gray-100' }}">
                                                            <span>
                                                                @if ($period->play_type == 'game')
                                                                    🎮 لعبة: {{ optional($period->game)->name ?: 'لعبة محذوفة' }}
                                                                @else
                                                                    ⏱️ وقت مفتوح
                                                                @endif
                                                                ({{ $period->controller_count }} {{ $period->controller_count > 1 ? 'أجهزة' : 'جهاز' }})
                                                            </span>
                                                            <span class="text-right">
                                                                {{ $period->start_time->format('h:i A') }} - {{ $period->end_time ? $period->end_time->format('h:i A') : 'الآن' }}
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            
                                            <p class="text-xl font-bold text-center text-green-500">التكلفة: <span x-text="formatCurrency(estimatedCost)"></span> جنيه</p>
                                        </div>
                                        
                                        <div class="mt-4 pt-4 border-t space-y-2">
                                            @if ($currentPeriod && $currentPeriod->play_type == 'game')
                                                <form action="{{ route('employee.play_sessions.add_game', $session->id) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                        إضافة جيم آخر
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <div class="flex gap-2">
                                                @if($session->alert_at)
                                                    <button @click="openAlertModal({{ $session->id }})" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-yellow-400 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-600 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                        تعديل المنبه
                                                    </button>
                                                    <form action="{{ route('employee.play_sessions.cancel_alert', $session->id) }}" method="POST" class="flex-1">
                                                        @csrf
                                                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                            إلغاء
                                                        </button>
                                                    </form>
                                                @else
                                                    <button @click="openAlertModal({{ $session->id }})" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-yellow-400 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-600 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                        ضبط منبه
                                                    </button>
                                                @endif
                                                <x-secondary-button @click="openSwitchModal({{ $session->id }}, {{ $device->games }}, {{ $device->pricings }})" class="flex-1 justify-center">تبديل</x-secondary-button>
                                            </div>
                                            
                                            <div>
                                                <form action="{{ route('employee.play_sessions.end', $session->id) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <x-danger-button type="submit" class="w-full justify-center" onclick="return confirm('هل أنت متأكد؟')">إنهاء الجلسة</x-danger-button>
                                                </form>
                                            </div>
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

        @include('employee.partials.modals')
    </div>
    
    @include('employee.partials.scripts')
</x-app-layout>