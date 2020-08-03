<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.partials.head')
    </head>
    <body>

        <div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
            <!--@include('layouts.partials.nav')-->
            @include('layouts.partials.header')
            @yield('content')
            @include('layouts.partials.footer')
            @include('layouts.partials.footer-scripts')
        </div>
    </body>

</html>