<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bugs Tracking system') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery (required for Select2) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    @vite('resources/css/app.css')
</head>

<body>
    <div id="app">
        @include('components.navbar')

        <main class="py-4 md:px-0 px-4 max-w-[1250px] mx-auto">
            @include('components.messages')
            @yield('content')
        </main>
    </div>



    <script>
        // For the selection of multiple QA's and Dev's
        $(document).ready(function() {
            $('#assignto').select2({
                placeholder: "Select Developer or QA",
                allowClear: true
            });
        });


        function updateBugStatusOptions() {
            const bugtype = document.getElementById('bugtype')?.value;
            const bugstatusSelect = document.getElementById('bugstatus');

            bugstatusSelect.innerHTML = '';

            if (bugtype === 'Feature' || bugtype === 'Bug') {
                bugstatusSelect.disabled = false;
                bugstatusSelect.style.cursor = "allowed";

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.text = 'Select a status';
                bugstatusSelect.appendChild(defaultOption);

                const optionsMap = {
                    Feature: ['New', 'Started', 'Completed'],
                    Bug: ['New', 'Started', 'Resolved']
                };

                optionsMap[bugtype].forEach(status => {
                    const option = document.createElement('option');
                    option.value = status;
                    option.text = status;
                    bugstatusSelect.appendChild(option);
                });
            } else {
                bugstatusSelect.disabled = true;
                bugstatusSelect.style.cursor = "not-allowed";
            }
        }
    </script>
</body>

</html>
