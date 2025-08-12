<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        {{ __('إضافة جهاز جديد') }}
                    </h2>
                    <form method="POST" action="{{ route('admin.devices.store') }}">
                        @csrf
                        
                        {{-- استدعاء الفورم المشترك --}}
                        @include('admin.devices._form')
                        
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('حفظ الجهاز') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>