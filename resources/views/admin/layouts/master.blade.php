<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    @include('admin.layouts.head')
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('admin.layouts.navbar')
            @include('admin.layouts.sidebar')

            @yield('content')

            @include('admin.layouts.footer')
        </div>
    </div>
    @include('admin.layouts.script')
</body>

</html>