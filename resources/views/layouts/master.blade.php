<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="SDD Brandcare - Prova tècnica Carles Malvesí">
        <title>{{ config('app.name') }} - @yield('page_title')</title>

        {{-- Bootstrap --}}
        <link rel="stylesheet" href="{{ url('css/bootstrap.css') }}">
    </head>

    <body class="container p-3">
        @section('navigation')
        <ul class="nav nav-pills my-3">
            <li class="nav-item mr-2">
                <a href="{{ route('images.create') }}" class="nav-link active">Upload image</a>
            </li>
            <li class="nav-item mr-2">
                <!--<a href="{{ route('images.index') }}" class="nav-link">View your images</a>-->
            </li>
        </ul>
        @show

        <h2>@yield('page_title')</h2>

        {{-- Info messages --}}
        @includeWhen(Session::has('success'), 'layouts.success')
        @includeWhen($errors->any(), 'layouts.error')

        @yield('content')

        @section('footer')
            <footer class="page-footer font-small p-4 bg-light">
                <p>Prova tècnica de <strong><a href="http://www.sddbrandcare.com/" target="_blank">SDD
                            Brandcare</a></strong> creada per <a href="https://www.linkedin.com/in/carlesmalvesi/" target="_blank">{{ $creator }}</a></p>
            </footer>
        @show
    </body>
</html>

