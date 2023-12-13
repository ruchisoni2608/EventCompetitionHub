@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Generate Text</h1>

        <input type="text" id="prompt" placeholder="Enter your prompt">
        <button onclick="generateText()">Generate</button>

        <div id="output"></div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function generateText() {
            const prompt = document.getElementById('prompt').value;
            const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

            // Make an AJAX request to your Laravel backend
            // Send the prompt, get the response, and update the output div
            // Example using jQuery:

            $.ajax({
                type: 'POST',
                url: '/generate-text',
                data: {
                    prompt: prompt
                },
                success: function(response) {
                    console.log('result:', response);
                    const generatedText = response.generatedText || 'No response';

                    document.getElementById('output').innerHTML = generatedText;
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }
    </script>
@endpush
