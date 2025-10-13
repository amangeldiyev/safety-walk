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

                $auditData = \App\Models\Audit::selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
                    ->whereYear('created_at', Carbon::now()->year)
                    ->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
                    ->orderBy('month')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'month' => Carbon::create()->month((int)$item->month)->format('F'),
                            'count' => $item->count,
                        ];
                    });
            @endphp
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const ctx = document.getElementById('auditBarChart').getContext('2d');
                    const auditData = @json($auditData); // Pass audit data from the controller
                    const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    const counts = labels.map(month => {
                        const data = auditData.find(item => item.month === month);
                        return data ? data.count : 0;
                    });

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Safety Walk',
                                data: counts,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
            </script>
        </div>
    </div>
@stop
