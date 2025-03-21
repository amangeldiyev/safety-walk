<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Safety Walk') }}
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
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-200 mb-4">Audit Overview</h3>
                <div class="mb-4 flex justify-center" style="max-height: 300px;">
                    <canvas id="auditPieChart" width="400" height="400"></canvas>
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        var ctx = document.getElementById('auditPieChart').getContext('2d');
                        var auditPieChart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: ['Completed', 'Not Completed'],
                                datasets: [{
                                    label: '',
                                    data: [
                                        {{ auth()->user()->actual_count }},
                                        {{ auth()->user()->target_count - auth()->user()->actual_count}}
                                    ],
                                    backgroundColor: [
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(255, 99, 132, 0.2)'
                                    ],
                                    borderColor: [
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 99, 132, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                    },
                                    title: {
                                        display: true,
                                        text: 'Safety walk progress'
                                    }
                                }
                            }
                        });
                    </script>
                </div>
            </div>
            <hr class="py-4 mt-8 border-t-2 border-gray-200 dark:border-gray-700">
            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-200 mb-4">Audit History</h3>
                <a href="{{ route('audits.create') }}" class="inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Create Audit</a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Site
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Mode
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Contact
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Is Virtual
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Comment
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Follow Up Date
                                </th>
                                {{-- <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Edit</span>
                                </th> --}}
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($audits as $audit)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                        <a href="{{ route('audits.show', $audit->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-500 block">{{ $audit->id }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $audit->site->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $audit->date }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ \App\Enums\AuditMode::tryFrom($audit->mode)?->name ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $audit->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $audit->is_virtual ? 'Yes' : 'No' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $audit->comment }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $audit->follow_up_date }}
                                    </td>
                                    {{-- <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('audits.show', $audit->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-500 block">View</a>
                                        <a href="{{ route('audits.edit', $audit->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-500 block">Edit</a>
                                        <a href="{{ route('audits.questions.create', $audit->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-500 block">Questions</a>
                                        <form action="{{ route('audits.destroy', $audit->id) }}" method="POST" class="inline block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-500">Delete</button>
                                        </form>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="py-4 mt-8 border-t-2 border-gray-200 dark:border-gray-700">
            <div class="mb-8">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-200 mb-4">Key Safety Behaviour</h3>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <ul class="list-disc pl-5">
                        
                        {{-- @foreach ($keySafetyBehaviours as $behaviour) --}}
                            <li class="flex justify-center mb-4">
                                <img src="/storage/{{setting('site.key_safety_image_1')}}" alt="Behaviour Image" class="object-cover rounded" style="width: 200px; height: auto; max-height: 150px;">
                                <div class="px-4">
                                    <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-200">Communication</h1>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{setting('site.key_safety_text_1')}}</span>
                                </div>
                            </li>
                            <li class="flex justify-center mb-4">
                                <img src="/storage/{{setting('site.key_safety_image_2')}}" alt="Behaviour Image" class="object-cover rounded" style="width: 200px; height: auto; max-height: 150px;">
                                <div class="px-4">
                                    <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-200">Risk assessment</h1>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{setting('site.key_safety_text_2')}}</span>
                                </div>
                            </li>
                            <li class="flex justify-center mb-4">
                                <img src="/storage/{{setting('site.key_safety_image_3')}}" alt="Behaviour Image" class="object-cover rounded" style="width: 200px; height: auto; max-height: 150px;">
                                <div class="px-4">
                                    <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-200">Involvement</h1>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{setting('site.key_safety_text_3')}}</span>
                                </div>
                            </li>
                        {{-- @endforeach --}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
