
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        @yield('stylesheets')
    </head>
    <body>
        @include('layouts.public.menu')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    @yield('content')
                </div>
                <div class="col-md-3">
                    @yield('sidebar')
                    <br>
                    calendario
                    <br>
                    partner
                    <br>
                    informazioni generali
                </div>
            </div>
        </div>

        <script src="{{ mix('/js/app.js') }}"></script>
        @yield('scripts')
    </body>
</html>
