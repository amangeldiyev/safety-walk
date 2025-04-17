<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Questions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('audits.questions.store', ['audit' => $audit->id]) }}" method="POST">
                        @csrf
                        @foreach($segments as $segment)
                            <div class="mb-6">
                                @if (Route::currentRouteName() === 'audits.questions.create')
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center" style="cursor: pointer">
                                        <a href="{{ route('audits.questions.segment', ['audit' => $audit->id, 'segment' => $segment->id]) }}" class="flex items-center">
                                            <span id="arrow_{{ $segment->id }}" class="mr-2">▶</span>{{ $segment->name }}
                                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400" id="completion_{{ $segment->id }}"></span>
                                        </a>
                                    </h3>
                                    <div id="segment_{{ $segment->id }}" class="segment-content">
                                        @foreach($segment->auditQuestions as $question)
                                            <div class="mb-4">
                                                <label for="question_{{ $question->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $question->question }}</label>
                                                <input type="text" id="question_{{ $question->id }}" name="questions[{{ $question->id }}]" class="mt-1 text-black block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ $audit->items->firstWhere('audit_question_id', $question->id)->answer ?? '' }}" oninput="updateCompletion({{ $segment->id }})">
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center" style="cursor: pointer" onclick="toggleSegment({{ $segment->id }})">
                                        <span id="arrow_{{ $segment->id }}" class="mr-2">▶</span>{{ $segment->name }}
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400" id="completion_{{ $segment->id }}"></span>
                                    </h3>
                                    <div id="segment_{{ $segment->id }}" class="">
                                        @foreach($segment->auditQuestions as $question)
                                            <div class="mb-4">
                                                <label for="question_{{ $question->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $question->question }}</label>
                                                <input type="text" id="question_{{ $question->id }}" name="questions[{{ $question->id }}]" class="mt-1 text-black block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ $audit->items->firstWhere('audit_question_id', $question->id)->answer ?? '' }}" oninput="updateCompletion({{ $segment->id }})">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <script>
                            function toggleSegment(segmentId) {
                                var segmentContent = document.getElementById('segment_' + segmentId);
                                var arrow = document.getElementById('arrow_' + segmentId);
                                if (segmentContent.style.display === 'none') {
                                    segmentContent.style.display = 'block';
                                    arrow.textContent = '▼';
                                } else {
                                    segmentContent.style.display = 'none';
                                    arrow.textContent = '▶';
                                }
                            }

                            function updateCompletion(segmentId) {
                                var segmentContent = document.getElementById('segment_' + segmentId);
                                var inputs = segmentContent.querySelectorAll('input[type="text"]');
                                var answered = 0;
                                inputs.forEach(function(input) {
                                    if (input.value.trim() !== '') {
                                        answered++;
                                    }
                                });
                                var completion = document.getElementById('completion_' + segmentId);
                                completion.textContent = answered + '/' + inputs.length + ' answered';
                            }

                            document.addEventListener('DOMContentLoaded', function() {
                                var segments = document.querySelectorAll('.segment-content');
                                segments.forEach(function(segment) {
                                    segment.style.display = 'none';
                                });
                                @foreach($segments as $segment)
                                    updateCompletion({{ $segment->id }});
                                @endforeach
                            });
                        </script>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>