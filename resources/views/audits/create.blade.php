<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Register Safety Walk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('audits.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                            <input type="date" id="date" name="date" class="mt-1 text-black block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mode</label>
                            <select id="mode" name="mode" class="mt-1 text-black block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                @foreach(\App\Enums\AuditMode::cases() as $mode)
                                    <option value="{{ $mode->value }}">{{ $mode->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="is_virtual" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Is Virtual</label>
                            <select id="is_virtual" name="is_virtual" class="mt-1 text-black block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="site_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Site</label>
                            <select id="site_id" name="site_id" class="mt-1 text-black block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}">{{ $site->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact</label>
                            <select id="contact" name="contact" class="mt-1 text-black block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="mb-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Comment</label>
                            <textarea id="comment" name="comment" rows="3" class="mt-1 text-black block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="follow_up_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Follow Up Date</label>
                            <input type="date" id="follow_up_date" name="follow_up_date" class="mt-1 text-black block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div> --}}
                        <button type="submit" class="inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Create Audit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>