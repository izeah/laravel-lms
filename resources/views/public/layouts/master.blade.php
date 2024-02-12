<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    @include('public.layouts.style')

    @yield('css')
</head>

<body>
    <div class="super_container">
        <!-- Header -->
        @include('public.layouts.header')

        <!-- Home -->
        @yield('slider')

        @yield('content')

        @include('public.layouts.footer')
    </div>

    @include('public.layouts.script')

    @yield('js')
</body>

</html>