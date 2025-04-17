<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Key Safety Behaviour') }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-300">
                Hi, {{ Auth::user()->name }}
            </p>
        </div>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-medium">{{session('success')}}</span>
                </div>
            @endif
            <div class="mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <ul class="list-disc pl-5">
                        
                        {{-- @foreach ($keySafetyBehaviours as $behaviour) --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                                    <img src="/storage/{{setting('site.key_safety_image_1')}}" alt="Behaviour Image" class="object-cover rounded mb-4" style="width: 100%; height: auto; max-height: 150px;">
                                    <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-200">Communication</h1>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{setting('site.key_safety_text_1')}}</span>
                                </div>
                                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                                    <img src="/storage/{{setting('site.key_safety_image_2')}}" alt="Behaviour Image" class="object-cover rounded mb-4" style="width: 100%; height: auto; max-height: 150px;">
                                    <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-200">Risk assessment</h1>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{setting('site.key_safety_text_2')}}</span>
                                </div>
                                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                                    <img src="/storage/{{setting('site.key_safety_image_3')}}" alt="Behaviour Image" class="object-cover rounded mb-4" style="width: 100%; height: auto; max-height: 150px;">
                                    <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-200">Involvement</h1>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{setting('site.key_safety_text_3')}}</span>
                                </div>
                            </div>
                        {{-- @endforeach --}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
