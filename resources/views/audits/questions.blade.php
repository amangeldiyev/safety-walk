<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Questions') }}
        </h2>
    </x-slot>
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

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
                                            @php
                                                $value = $audit->items->firstWhere('audit_question_id', $question->id)->answer ?? '';
                                            @endphp
                                            <div class="mb-4">
                                                <label for="question_{{ $question->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">asdasd{{$question->input_type}} - {{ $question->question }}</label>
                                                @switch($question->input_type)

                                                    @case(\App\Enums\InputType::TEXTAREA->value)
                                                        <textarea
                                                            id="question_{{ $question->id }}"
                                                            name="questions[{{ $question->id }}]"
                                                            class="mt-1 text-black block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                                                            oninput="updateCompletion({{ $segment->id }})"
                                                        >{{ $value }}</textarea>
                                                    @break

                                                    @default
                                                        <input
                                                            type="text"
                                                            id="question_{{ $question->id }}"
                                                            name="questions[{{ $question->id }}]"
                                                            class="mt-1 text-black block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                            value="{{ $value }}"
                                                            oninput="updateCompletion({{ $segment->id }})">
                                                @endswitch
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
                                            @php
                                                $value = $audit->items->firstWhere('audit_question_id', $question->id)->answer ?? '';
                                            @endphp
                                            <div class="mb-4">
                                                <label for="question_{{ $question->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $question->question }}</label>
                                                @switch($question->input_type)

                                                    @case(\App\Enums\InputType::TEXTAREA->value)
                                                        <textarea
                                                            id="question_{{ $question->id }}"
                                                            name="questions[{{ $question->id }}]"
                                                            class="mt-1 text-black block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                                                            oninput="updateCompletion({{ $segment->id }})"
                                                            rows="3"
                                                        >{{ $value }}</textarea>
                                                    @break

                                                    @case(\App\Enums\InputType::YESNO->value)
                                                        @php
                                                            $options = ['Yes', 'No'];
                                                        @endphp
                                                        <select 
                                                            id="question_{{ $question->id }}" 
                                                            name="questions[{{ $question->id }}]"
                                                            class="mt-1 text-black block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                                                            onchange="updateCompletion({{ $segment->id }})"
                                                        >
                                                            <option value="">Select…</option>
                                                            @foreach($options as $opt)
                                                                <option value="{{ trim($opt) }}" @selected($value == trim($opt))>{{ trim($opt) }}</option>
                                                            @endforeach
                                                        </select>
                                                    @break

                                                    @case(\App\Enums\InputType::SELECT->value)
                                                        @php
                                                            $options = explode(';', $question->inputs);
                                                        @endphp
                                                        <select 
                                                            id="question_{{ $question->id }}" 
                                                            name="questions[{{ $question->id }}]"
                                                            class="mt-1 text-black block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                                                            onchange="updateCompletion({{ $segment->id }})"
                                                        >
                                                            <option value="">Select…</option>
                                                            @foreach($options as $opt)
                                                                <option value="{{ trim($opt) }}" @selected($value == trim($opt))>{{ trim($opt) }}</option>
                                                            @endforeach
                                                        </select>
                                                    @break

                                                    @case(\App\Enums\InputType::MULTISELECT->value)
                                                        @php
                                                            $options = explode(';', $question->inputs);

                                                            if (is_string($value)) {
                                                                $selectedValues = explode(';', $value);
                                                            }
                                                        @endphp

                                                        <select
                                                            id="question_{{ $question->id }}"
                                                            name="questions[{{ $question->id }}][]"
                                                            multiple
                                                            class="tomselect w-full text-black"
                                                        >
                                                            @foreach($options as $opt)
                                                                <option 
                                                                    value="{{ trim($opt) }}"
                                                                    @if(in_array(trim($opt), $selectedValues)) selected @endif
                                                                >
                                                                    {{ trim($opt) }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <script>
                                                            document.addEventListener("DOMContentLoaded", function() {
                                                                new TomSelect('#question_{{ $question->id }}', {
                                                                    plugins: ['remove_button'],
                                                                    persist: false,
                                                                    create: false,
                                                                    onChange: function() {
                                                                        console.log('asdasd');
                                                                        
                                                                        updateCompletion({{ $segment->id }});
                                                                    }
                                                                });
                                                            });
                                                        </script>
                                                    @break

                                                    @default
                                                        <input
                                                            type="text"
                                                            id="question_{{ $question->id }}"
                                                            name="questions[{{ $question->id }}]"
                                                            class="mt-1 text-black block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                            value="{{ $value }}"
                                                            oninput="updateCompletion({{ $segment->id }})">
                                                @endswitch
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
                                const segmentContent = document.getElementById('segment_' + segmentId);

                                if (!segmentContent) return;

                                // Select all form elements inside the segment
                                const fields = segmentContent.querySelectorAll(
                                    'input[type="text"], input[type="number"], input[type="date"], textarea, select'
                                );
                                console.log(fields);
                                

                                let total = 0;
                                let answered = 0;

                                fields.forEach(field => {
                                    total++;

                                    // TEXT / NUMBER / DATE / TEXTAREA
                                    if (['INPUT', 'TEXTAREA'].includes(field.tagName)) {

                                        // Checkbox → check if checked
                                        if (field.type === "checkbox") {
                                            if (field.checked) answered++;
                                            return;
                                        }

                                        // Text, number, date
                                        if (field.value.trim() !== '') {
                                            answered++;
                                        }
                                        return;
                                    }

                                    // SELECT (single)
                                    if (field.tagName === 'SELECT' && !field.multiple) {
                                        if (field.value.trim() !== '') {
                                            answered++;
                                        }
                                        return;
                                    }

                                    // MULTISELECT
                                    if (field.tagName === 'SELECT' && field.multiple) {

                                        // TomSelect stores selected items in `field.tomselect.items`
                                        if (field.tomselect) {
                                            if (field.tomselect.items.length > 0) answered++;
                                            return;
                                        }

                                        // Native multiselect fallback
                                        const selectedOptions = [...field.options].filter(o => o.selected);
                                        if (selectedOptions.length > 0) answered++;
                                    }
                                });

                                const completion = document.getElementById('completion_' + segmentId);
                                if (completion) {
                                    completion.textContent = `${answered}/${total} answered`;
                                }
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