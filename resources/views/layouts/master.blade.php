<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="SDD Brandcare - Prova tècnica Carles Malvesí">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css"/>
        <title>{{ config('app.name') }} - @yield('page_title')</title>
    </head>

    <body class="container p-3">
        <h2>@yield('page_title')</h2>

        {{-- Info messages --}}
        @includeWhen(Session::has('success'), 'layouts.success')
        @includeWhen($errors->any(), 'layouts.error')

        {{-- Main content --}}
        @yield('content')

        {{-- Footer page --}}
        @extends('layouts.footer')

        {{-- Scripts section --}}
        @yield('javascript')
    </body>
</html>

