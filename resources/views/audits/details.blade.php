<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Safety Walk Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-medium">{{session('success')}}</span>
                </div>
            @endif
            <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

            <form action="{{ route('audits.details.store', $audit->id) }}" method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
                @csrf
                @if ($audit->mode == (\App\Enums\AuditMode::CONVERSATION)->value)
                    <div class="mb-4">
                        <div class="flex items-center">
                            <input id="good_practice" name="good_practice" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-indigo-600">
                            <label for="good_practice" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Good practice</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="flex items-center">
                            <input id="point_of_improvement" name="point_of_improvement" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-indigo-600">
                            <label for="point_of_improvement" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Point of improvement</label>
                        </div>
                    </div>
                @endif
                
                <div class="mb-4">
                    <label for="audit_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $audit->mode == (\App\Enums\AuditMode::GUIDED)->value ? 'Comments' : 'Audit Description' }}</label>
                    <button type="button" id="recordBtn">🎤 Record Voice</button>
                    <textarea name="comment" id="audit_description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"></textarea>
                </div>
                <div class="mb-4" id="follow_up_date_container" style="display: none;">
                    <label for="follow_up_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Follow Up Date</label>
                    <input type="date" name="follow_up_date" id="follow_up_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const pointOfImprovementCheckbox = document.getElementById('point_of_improvement');
                        const followUpDateContainer = document.getElementById('follow_up_date_container');

                        pointOfImprovementCheckbox.addEventListener('change', function () {
                            if (this.checked) {
                                followUpDateContainer.style.display = 'block';
                            } else {
                                followUpDateContainer.style.display = 'none';
                            }
                        });
                    });
                </script>

                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">Add Images(5MB)</h3>
                    <div class="flex items-center">
                        <input type="file" name="attachments[]" multiple class="block w-full text-sm text-gray-900 dark:text-gray-300 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">Signature</h3>
                    <div style="border: 1px solid #000; width: 400px; height: 200px;">
                        <canvas id="signature-pad" width="400" height="200" style="background-color: lemonchiffon"></canvas>
                    </div>
                    <button type="button" id="clear" style="color: brown">Clear</button>
                    <input type="hidden" name="signature" id="signature-input">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Sign & Submit</button>
                </div>
            </form>

            <script>
                const canvas = document.getElementById('signature-pad');
                const signaturePad = new SignaturePad(canvas);
                const signatureInput = document.getElementById('signature-input');
            
                function submitForm(event) {
                    if (!signaturePad.isEmpty()) {
                        signatureInput.value = signaturePad.toDataURL(); // Save signature
                    }
                }
                            
                // Clear signature
                document.getElementById('clear').addEventListener('click', function () {
                    signaturePad.clear();
                });

                document.addEventListener("DOMContentLoaded", function () {
                    const recordBtn = document.getElementById("recordBtn");
                    const descriptionField = document.getElementById("audit_description");
                    let recognition;
                    let isRecording = false;

                    if ("webkitSpeechRecognition" in window || "SpeechRecognition" in window) {
                        recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
                        recognition.continuous = true; // Allow continuous recognition
                        recognition.interimResults = false;
                        recognition.lang = "en-US";

                        recordBtn.addEventListener("click", function () {
                            if (!isRecording) {
                                recognition.start();
                                isRecording = true;
                                recordBtn.innerText = "🛑 Stop Recording";
                            } else {
                                recognition.stop();
                                isRecording = false;
                                recordBtn.innerText = "🎤 Start Recording";
                            }
                        });

                        recognition.onresult = function (event) {
                            let finalTranscript = '';
                            for (let i = event.resultIndex; i < event.results.length; ++i) {
                                if (event.results[i].isFinal) {
                                    finalTranscript += event.results[i][0].transcript + ' ';
                                }
                            }
                            descriptionField.value += finalTranscript.trim() + "\n";
                        };

                        recognition.onerror = function (event) {
                            console.error("Speech recognition error:", event);
                            recordBtn.innerText = "🎤 Start Recording";
                            isRecording = false;
                        };

                        recognition.onend = function () {
                            // Optional: Automatically update UI when recognition ends (e.g., due to silence)
                            if (isRecording) {
                                // Restart if needed — useful if you want continuous retry
                                recognition.start();
                            } else {
                                recordBtn.innerText = "🎤 Start Recording";
                            }
                        };
                    } else {
                        alert("Speech recognition is not supported in your browser.");
                    }
                });
            </script>
        </div>
    </div>
</x-app-layout>
