<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        {{ __('لوحة تحكم المدير') }}
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        
                        {{-- تم تطبيق مجموعة ألوان متناسقة على البطاقات --}}

                        <div class="bg-blue-50 p-6 rounded-lg shadow-md border border-blue-200">
                            <h3 class="text-lg font-semibold text-blue-800">إجمالي المستخدمين</h3>
                            <p class="text-3xl font-bold text-blue-900 mt-2">{{ $stats['users_count'] }}</p>
                        </div>

                        <div class="bg-orange-50 p-6 rounded-lg shadow-md border border-orange-200">
                            <h3 class="text-lg font-semibold text-orange-800">إجمالي الأجهزة</h3>
                            <p class="text-3xl font-bold text-orange-900 mt-2">{{ $stats['devices_count'] }}</p>
                        </div>

                        <div class="bg-purple-50 p-6 rounded-lg shadow-md border border-purple-200">
                            <h3 class="text-lg font-semibold text-purple-800">إجمالي الألعاب</h3>
                            <p class="text-3xl font-bold text-purple-900 mt-2">{{ $stats['games_count'] }}</p>
                        </div>

                        <div class="bg-green-50 p-6 rounded-lg shadow-md border border-green-200">
                            <h3 class="text-lg font-semibold text-green-800">الجلسات النشطة حالياً</h3>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['active_sessions_count'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>