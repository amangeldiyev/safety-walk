<x-app-layout>
    {{-- <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-300">
                Hi, {{ Auth::user()->name }}
            </p>
        </div>
    </x-slot> --}}

    <div class="py-12 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-medium">{{session('success')}}</span>
                </div>
            @endif
            <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                <div
                    id="docs-card"
                    class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] border border-black/40 transition duration-300 hover:text-black/60 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10 lg:pb-10"
                >
                    <div class="w-full mb-8 p-4 rounded-lg shadow-md">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 text-center">Safety Walk Overview</h3>
                        <div class="mb-4 flex justify-center items-center" style="max-height: 600px;">
                            <canvas id="auditPieChart" width="auto" height="200"></canvas>
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

                    {{-- <div class="relative flex items-center gap-6 lg:items-end">
                        <div id="docs-card-content" class="flex items-start gap-6 lg:flex-col">
                            <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16">
                                <svg class="size-5 sm:size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path fill="#FF2D20" d="M23 4a1 1 0 0 0-1.447-.894L12.224 7.77a.5.5 0 0 1-.448 0L2.447 3.106A1 1 0 0 0 1 4v13.382a1.99 1.99 0 0 0 1.105 1.79l9.448 4.728c.14.065.293.1.447.1.154-.005.306-.04.447-.105l9.453-4.724a1.99 1.99 0 0 0 1.1-1.789V4ZM3 6.023a.25.25 0 0 1 .362-.223l7.5 3.75a.251.251 0 0 1 .138.223v11.2a.25.25 0 0 1-.362.224l-7.5-3.75a.25.25 0 0 1-.138-.22V6.023Zm18 11.2a.25.25 0 0 1-.138.224l-7.5 3.75a.249.249 0 0 1-.329-.099.249.249 0 0 1-.033-.12V9.772a.251.251 0 0 1 .138-.224l7.5-3.75a.25.25 0 0 1 .362.224v11.2Z"/><path fill="#FF2D20" d="m3.55 1.893 8 4.048a1.008 1.008 0 0 0 .9 0l8-4.048a1 1 0 0 0-.9-1.785l-7.322 3.706a.506.506 0 0 1-.452 0L4.454.108a1 1 0 0 0-.9 1.785H3.55Z"/></svg>
                            </div>

                            <div class="pt-3 sm:pt-5 lg:pt-0">
                                <h2 class="text-xl font-semibold text-black">Empowering Workplace Safety</h2>

                                <p class="mt-4 text-sm/relaxed">
                                    The Safety Walk application is a user-friendly digital tool designed to streamline and enhance workplace safety inspections. It empowers safety officers and supervisors to efficiently conduct safety walks, identify potential hazards, document observations, assign corrective actions, and ensure compliance with safety protocols.
                                </p>
                            </div>
                        </div>

                        <svg class="size-6 shrink-0 stroke-[#FF2D20]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/></svg>
                    </div> --}}
                </div>
                <div class="flex flex-col gap-6 lg:gap-8">
                    <a
                        href="{{ route('audits.create') }}"
                        class="flex items-start gap-4 rounded-lg border border-black/40 p-4 bg-white shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-black/30 ring-white/[0.05] transition duration-300 hover:text-black/70 ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] ring-black/40"
                    >
                        <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16">
                            {{-- <svg class="size-5 sm:size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><g fill="#FF2D20"><path d="M24 8.25a.5.5 0 0 0-.5-.5H.5a.5.5 0 0 0-.5.5v12a2.5 2.5 0 0 0 2.5 2.5h19a2.5 2.5 0 0 0 2.5-2.5v-12Zm-7.765 5.868a1.221 1.221 0 0 1 0 2.264l-6.626 2.776A1.153 1.153 0 0 1 8 18.123v-5.746a1.151 1.151 0 0 1 1.609-1.035l6.626 2.776ZM19.564 1.677a.25.25 0 0 0-.177-.427H15.6a.106.106 0 0 0-.072.03l-4.54 4.543a.25.25 0 0 0 .177.427h3.783c.027 0 .054-.01.073-.03l4.543-4.543ZM22.071 1.318a.047.047 0 0 0-.045.013l-4.492 4.492a.249.249 0 0 0 .038.385.25.25 0 0 0 .14.042h5.784a.5.5 0 0 0 .5-.5v-2a2.5 2.5 0 0 0-1.925-2.432ZM13.014 1.677a.25.25 0 0 0-.178-.427H9.101a.106.106 0 0 0-.073.03l-4.54 4.543a.25.25 0 0 0 .177.427H8.4a.106.106 0 0 0 .073-.03l4.54-4.543ZM6.513 1.677a.25.25 0 0 0-.177-.427H2.5A2.5 2.5 0 0 0 0 3.75v2a.5.5 0 0 0 .5.5h1.4a.106.106 0 0 0 .073-.03l4.54-4.543Z"/></g></svg> --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 sm:size-6" viewBox="0 0 24 24" fill="none">
                                <g fill="#FF2D20">
                                  <!-- Clipboard -->
                                  <rect x="4" y="3" width="16" height="18" rx="2" ry="2" stroke="#FF2D20" stroke-width="2" fill="none"/>
                                  <rect x="8" y="1" width="8" height="4" rx="1" ry="1" fill="#FF2D20"/>
                              
                                  <!-- Checkmark -->
                                  <path d="M8 10l2 5 6-7" stroke="#FF2D20" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                              
                                  <!-- Walking person -->
                                  {{-- <path d="M12 17c1 0 2-1 2-2s-1-2-2-2-2 1-2 2 1 2 2 2Zm0 0v2m0 0-2 2m2-2 2 2" stroke="#FF2D20" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> --}}
                                </g>
                            </svg>
                        </div>
                          
    
                        <div href="{{ route('audits.create') }}" class="pt-3 sm:pt-5">
                            <h2 class="text-xl font-semibold text-black">Register Safety Walk</h2>
                        </div>
                    </a>
                    <div
                        class="flex items-start gap-4 rounded-lg border border-black/40 p-4 bg-white shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-black/30 ring-white/[0.05] transition duration-300 hover:text-black/70 ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] ring-black/40"
                    >
                        <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16">
                            <svg class="size-5 sm:size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><g fill="#FF2D20"><path d="M24 8.25a.5.5 0 0 0-.5-.5H.5a.5.5 0 0 0-.5.5v12a2.5 2.5 0 0 0 2.5 2.5h19a2.5 2.5 0 0 0 2.5-2.5v-12Zm-7.765 5.868a1.221 1.221 0 0 1 0 2.264l-6.626 2.776A1.153 1.153 0 0 1 8 18.123v-5.746a1.151 1.151 0 0 1 1.609-1.035l6.626 2.776ZM19.564 1.677a.25.25 0 0 0-.177-.427H15.6a.106.106 0 0 0-.072.03l-4.54 4.543a.25.25 0 0 0 .177.427h3.783c.027 0 .054-.01.073-.03l4.543-4.543ZM22.071 1.318a.047.047 0 0 0-.045.013l-4.492 4.492a.249.249 0 0 0 .038.385.25.25 0 0 0 .14.042h5.784a.5.5 0 0 0 .5-.5v-2a2.5 2.5 0 0 0-1.925-2.432ZM13.014 1.677a.25.25 0 0 0-.178-.427H9.101a.106.106 0 0 0-.073.03l-4.54 4.543a.25.25 0 0 0 .177.427H8.4a.106.106 0 0 0 .073-.03l4.54-4.543ZM6.513 1.677a.25.25 0 0 0-.177-.427H2.5A2.5 2.5 0 0 0 0 3.75v2a.5.5 0 0 0 .5.5h1.4a.106.106 0 0 0 .073-.03l4.54-4.543Z"/></g></svg>
                        </div>
    
                        <a href="{{ route('key-safety-behaviour') }}" class="pt-3 sm:pt-5">
                            <h2 class="text-xl font-semibold text-black">Key Safety Behaviour</h2>
                        </a>
                    </div>
    
                    <div
                        class="flex items-start gap-4 rounded-lg border border-black/40 bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-black/30  ring-white/[0.05] transition duration-300 hover:text-black/70 ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 ring-black/40"
                    >
                        <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16">
                            <svg class="size-5 sm:size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><g fill="#FF2D20"><path d="M8.75 4.5H5.5c-.69 0-1.25.56-1.25 1.25v4.75c0 .69.56 1.25 1.25 1.25h3.25c.69 0 1.25-.56 1.25-1.25V5.75c0-.69-.56-1.25-1.25-1.25Z"/><path d="M24 10a3 3 0 0 0-3-3h-2V2.5a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2V20a3.5 3.5 0 0 0 3.5 3.5h17A3.5 3.5 0 0 0 24 20V10ZM3.5 21.5A1.5 1.5 0 0 1 2 20V3a.5.5 0 0 1 .5-.5h14a.5.5 0 0 1 .5.5v17c0 .295.037.588.11.874a.5.5 0 0 1-.484.625L3.5 21.5ZM22 20a1.5 1.5 0 1 1-3 0V9.5a.5.5 0 0 1 .5-.5H21a1 1 0 0 1 1 1v10Z"/><path d="M12.751 6.047h2a.75.75 0 0 1 .75.75v.5a.75.75 0 0 1-.75.75h-2A.75.75 0 0 1 12 7.3v-.5a.75.75 0 0 1 .751-.753ZM12.751 10.047h2a.75.75 0 0 1 .75.75v.5a.75.75 0 0 1-.75.75h-2A.75.75 0 0 1 12 11.3v-.5a.75.75 0 0 1 .751-.753ZM4.751 14.047h10a.75.75 0 0 1 .75.75v.5a.75.75 0 0 1-.75.75h-10A.75.75 0 0 1 4 15.3v-.5a.75.75 0 0 1 .751-.753ZM4.75 18.047h7.5a.75.75 0 0 1 .75.75v.5a.75.75 0 0 1-.75.75h-7.5A.75.75 0 0 1 4 19.3v-.5a.75.75 0 0 1 .75-.753Z"/></g></svg>
                        </div>
                        @php $monthly = json_decode(setting('site.monthly_topic_file')); @endphp
                        @if(!empty($monthly[0]->download_link))
                            <a href="storage/{{ json_decode(setting('site.monthly_topic_file'))[0]->download_link }}" target="_blank" class="pt-3 sm:pt-5">
                                <h2 class="text-xl font-semibold text-black">Monthly Topic</h2>
        
                                <p class="mt-4 text-sm/relaxed">
                                    {{setting('site.monthly_topic')}}
                                </p>
                            </a>
                        @endif
                    </div>
                </div>
                
            </div>
            {{-- <hr class="py-4 mt-8 border-t-2 border-gray-200 dark:border-gray-700"> --}}
        </div>
    </div>
</x-app-layout>
