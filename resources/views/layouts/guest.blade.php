<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EduChat') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <script src="https://cdn.tailwindcss.com"></script>

        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            primary: '#4F46E5',
                            secondary: '#10B981',
                            dark: '#0f172a',
                            darker: '#020617',
                        }
                    }
                }
            }
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100 dark:bg-slate-900">
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            
            <div class="mb-6">
                <a href="/" class="flex items-center gap-2 text-3xl font-bold text-primary">
                    <i class="fas fa-graduation-cap"></i>
                    <span>EduChat</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-slate-800 shadow-md overflow-hidden sm:rounded-2xl border border-gray-200 dark:border-slate-700">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center text-xs text-gray-400">
                &copy; 2025 EduChat. All rights reserved.
            </div>
        </div>
        
    </body>
</html>