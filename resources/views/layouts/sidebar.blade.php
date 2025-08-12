<aside class="w-full h-full flex flex-col">
    <div class="p-4">
        @php
            $dashboardRoute = auth()->user()->role === 'admin' ? route('admin.dashboard') : route('employee.dashboard');
        @endphp
        <a href="{{ $dashboardRoute }}" class="flex items-center justify-center">
            <x-application-logo class="block h-10 w-auto fill-current" />
            <span class="ms-2 font-bold">{{ config('app.name', 'Laravel') }}</span>
        </a>
    </div>

    <nav class="mt-4 flex-grow">
        {{-- الروابط العامة --}}
        <a href="{{ $dashboardRoute }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 @if(request()->routeIs('admin.dashboard') || request()->routeIs('employee.dashboard')) bg-gray-700 @endif">
            لوحة التحكم
        </a>
        
        {{-- روابط المدير فقط --}}
        @if(auth()->user()->role === 'admin')
            {{-- تم تعديل هذا الرابط --}}
            <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 @if(request()->routeIs('admin.users.*')) bg-gray-700 @endif">
                إدارة المستخدمين
            </a>
            <a href="{{ route('admin.devices.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 @if(request()->routeIs('admin.devices.*')) bg-gray-700 @endif">
                إدارة الأجهزة
            </a>
            <a href="{{ route('admin.games.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 @if(request()->routeIs('admin.games.*')) bg-gray-700 @endif">
                إدارة الألعاب
            </a>
        @endif

        {{-- روابط الموظف فقط (يمكنك إضافة روابط هنا مستقبلاً) --}}
        @if(auth()->user()->role === 'employee')
            {{--  --}}
        @endif

        {{-- رابط الملف الشخصي --}}
        <a href="{{ route('profile.edit') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 @if(request()->routeIs('profile.edit')) bg-gray-700 @endif">
            الملف الشخصي
        </a>
    </nav>
    
    <div>
        <div class="p-4 border-t border-gray-700">
             <div class="font-semibold">{{ Auth::user()->name }}</div>
             <div class="text-sm text-gray-400">{{ Auth::user()->email }}</div>
             
             <form method="POST" action="{{ route('logout') }}" class="mt-2">
                 @csrf
                 <button type="submit" class="w-full text-right bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-200">
                     تسجيل الخروج
                 </button>
             </form>
        </div>
    </div>
</aside>