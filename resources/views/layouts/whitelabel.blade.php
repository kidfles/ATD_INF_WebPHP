<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $company->name ?? 'Company Page' }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-color: {{ $company->primary_color ?? '#3b82f6' }};
            --brand-text: {{ $company->secondary_color ?? '#ffffff' }}; /* Assuming secondary is text color for now */
        }
        body {
            background-color: var(--brand-color);
            color: var(--brand-text);
        }
    </style>
</head>
<body class="font-sans antialiased">
    {{ $slot }}
</body>
</html>
