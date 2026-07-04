<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @livewireStyles
    <style>
        /* Livewire default styles */
        [wire\:loading], [wire\:target] {
            opacity: 0.5;
            transition: opacity 0.3s ease;
        }
        
        [wire\:loading\.delay] {
            opacity: 0;
            display: none;
        }
    </style>
</head>
<body>
    @yield('content')
    
    @livewireScripts
    <script>
        // Livewire initialization
        document.addEventListener('livewire:init', () => {
            console.log('Livewire initialized');
        });
    </script>
</body>
</html>
