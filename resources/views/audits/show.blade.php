<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Audit Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-medium">{{session('success')}}</span>
                </div>
            @endif
            <div class="mb-4">
                <a href="{{ route('audits.index') }}" class="inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Back to Audits</a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">ID</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-300">{{ $audit->id }}</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">Site</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-300">{{ $audit->site->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">Date</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-300">{{ $audit->date }}</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">Mode</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-300">{{ \App\Enums\AuditMode::tryFrom($audit->mode)?->name ?? '' }}</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">Contact</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-300">{{ $audit->user->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">Is Virtual</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-300">{{ $audit->is_virtual ? 'Yes' : 'No' }}</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">Comment</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-300">{{ $audit->comment }}</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">Follow Up Date</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-300">{{ $audit->follow_up_date }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">Attachments</h3>
                    <form action="{{ route('audits.attachments.store', $audit->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <div class="flex items-center">
                            <input type="file" name="attachment" class="block w-full text-sm text-gray-900 dark:text-gray-300 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Upload</button>
                        </div>
                    </form>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">Existing Attachments</h3>
                    <ul class="mt-4 space-y-2">
                        @foreach ($audit->attachments as $attachment)
                            <li class="flex items-center justify-between mb-4">
                                <div>
                                    @if (in_array(pathinfo($attachment->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                        <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $attachment->file_path) }}" alt="{{ $attachment->name }}" class="w-20 h-20 object-cover">
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="text-white dark:text-white-400 hover:underline">{{ $attachment->file_name }}</a>
                                    @endif
                                </div>
                                <form action="{{ route('audits.attachments.destroy', [$audit->id, $attachment->id]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-500">Delete</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('audits.edit', $audit->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-500 mr-4">Edit</a>
                    <form action="{{ route('audits.destroy', $audit->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-500">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
