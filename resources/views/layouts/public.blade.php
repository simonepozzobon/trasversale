@php
    if (!isset($active)) {
        $active = '';
    }

    if (!isset($subactive)) {
        $subactive = '';
    }

    $pages = App\Utility::generate_menu();
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        @yield('stylesheets')
    </head>
    <body>
        {{-- @include('layouts.public.menu', ['active' => $active, 'subactive' => $subactive]) --}}
        <div id="app" class="mt-3">
            {{-- <div class="row">
                <div class="col-md-9">
                    @yield('content')
                </div>
                <div class="col-md-3">
                    <div class="pb-4">
                        @yield('sidebar')
                    </div>
                    <sidebar-calendar></sidebar-calendar>
                    <sidebar-information></sidebar-information>
                </div>
            </div> --}}
            <main-template pages="{{ $pages }}">
            </main-template>
        </div>

        <script src="{{ mix('/js/app.js') }}"></script>
        @yield('scripts')
    </body>
</html>
