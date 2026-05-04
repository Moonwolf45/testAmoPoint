<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-xl shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Динамическая форма</h1>

            <form id="mainForm" class="space-y-5">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Тип</label>
                    <select id="type" name="type" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="personal" selected>Личные данные</option>
                        <option value="company">Организация</option>
                        <option value="service">Услуга</option>
                    </select>
                </div>

                <!-- Группа: Личные данные -->
                <div class="field-block">
                    <label for="personal_name" class="block text-sm font-medium text-gray-700 mb-1">Имя</label>
                    <input type="text" name="personal_name" id="personal_name" class="w-full border border-gray-300 rounded-lg p-2.5">
                </div>
                <div class="field-block">
                    <label for="personal_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="personal_email" id="personal_email" class="w-full border border-gray-300 rounded-lg p-2.5">
                </div>

                <!-- Группа: Организация -->
                <div class="field-block">
                    <label for="company_inn" class="block text-sm font-medium text-gray-700 mb-1">ИНН</label>
                    <input type="text" name="company_inn" id="company_inn" class="w-full border border-gray-300 rounded-lg p-2.5">
                </div>
                <div class="field-block">
                    <label for="company_title" class="block text-sm font-medium text-gray-700 mb-1">Название компании</label>
                    <input type="text" name="company_title" id="company_title" class="w-full border border-gray-300 rounded-lg p-2.5">
                </div>

                <!-- Группа: Услуга -->
                <div class="field-block">
                    <label for="service_name" class="block text-sm font-medium text-gray-700 mb-1">Название услуги</label>
                    <input type="text" name="service_name" id="service_name" class="w-full border border-gray-300 rounded-lg p-2.5">
                </div>
                <div class="field-block">
                    <label for="service_description" class="block text-sm font-medium text-gray-700 mb-1">Описание</label>
                    <textarea name="service_description" id="service_description" class="w-full border border-gray-300 rounded-lg p-2.5"></textarea>
                </div>
            </form>
        </div>
    </body>
</html>
