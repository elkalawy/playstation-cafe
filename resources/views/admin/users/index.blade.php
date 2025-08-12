<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('إدارة المستخدمين') }}
                        </h2>
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">
                            {{ __('إضافة مستخدم') }}
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($users as $user)
                        <div class="bg-white rounded-lg shadow-md p-5 border flex flex-col justify-between">
                            <div>
                                <div class="flex items-center mb-3">
                                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center mr-4 shrink-0">
                                        <span class="text-lg font-bold text-gray-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800">{{ $user->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="my-3">
                                    @if($user->role === 'admin')
                                        <span class="inline-block bg-red-100 text-red-800 rounded-full px-3 py-1 text-xs font-semibold">مدير</span>
                                    @else
                                        <span class="inline-block bg-green-100 text-green-800 rounded-full px-3 py-1 text-xs font-semibold">موظف</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex justify-end items-center pt-3 border-t mt-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">تعديل</a>
                                {{-- لا تسمح للمستخدم بحذف نفسه --}}
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');" class="mr-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">حذف</button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>