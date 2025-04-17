<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
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
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-200 mb-4">Safety Walk Overview</h3>
                <div class="mb-4 flex justify-center" style="max-height: 600px;">
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
        </div>
    </div>
</x-app-layout>
