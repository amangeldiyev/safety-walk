@extends('voyager::master')

@section('content')
    <div class="page-content">
        @include('voyager::alerts')
        @include('voyager::dimmers')
        {{-- <h2 style="margin: 20px 50px">Dashboard</h2> --}}
        <div>
            <div class="Chart" style="display: flex; padding: 50px">
                <canvas id="auditBarChart" style="width: 100%; max-width: 50%; max-height: 400px"></canvas>
            </div>
            <style>
                #auditBarChart {
                    width: 100% !important;
                    height: 400px !important;
                    display: block !important;
                }
                @media (max-width: 768px) {
                    #auditBarChart {
                        max-width: 100% !important;
                    }
                }
            </style>

            @php
                use Carbon\Carbon;

                $currentYear = Carbon::now()->year;
                $previousYear = Carbon::now()->subYear()->year;

                $currentYearData = \App\Models\Audit::selectRaw('EXTRACT(MONTH FROM date) as month, COUNT(*) as count')
                    ->whereYear('date', $currentYear)
                    ->groupBy(DB::raw('EXTRACT(MONTH FROM date)'))
                    ->orderBy('month')
                    ->get()
                    ->map(fn ($item) => [
                        'month' => Carbon::create()->month((int)$item->month)->format('F'),
                        'count' => $item->count,
                    ]);

                $previousYearData = \App\Models\Audit::selectRaw('EXTRACT(MONTH FROM date) as month, COUNT(*) as count')
                    ->whereYear('date', $previousYear)
                    ->groupBy(DB::raw('EXTRACT(MONTH FROM date)'))
                    ->orderBy('month')
                    ->get()
                    ->map(fn ($item) => [
                        'month' => Carbon::create()->month((int)$item->month)->format('F'),
                        'count' => $item->count,
                    ]);
            @endphp
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const ctx = document.getElementById('auditBarChart').getContext('2d');

                    const currentYearData = @json($currentYearData);
                    const previousYearData = @json($previousYearData);

                    const labels = [
                        'January','February','March','April','May','June',
                        'July','August','September','October','November','December'
                    ];

                    const currentCounts = labels.map(month =>
                        currentYearData.find(i => i.month === month)?.count ?? 0
                    );

                    const previousCounts = labels.map(month =>
                        previousYearData.find(i => i.month === month)?.count ?? 0
                    );

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: '{{ $currentYear }}',
                                    data: currentCounts,
                                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                                },
                                {
                                    label: '{{ $previousYear }}',
                                    data: previousCounts,
                                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                });
            </script>
        </div>
    </div>
@stop
