<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! Meta::render() !!}
    @include('partials.styles')
</head>
<body>
    @include('partials.header')
    <div class="content">
        @widget__categories_menu()
        <div class="container">
            @widget__breadcrumbs($breadcrumbs)
    @yield('content')
        </div>
    </div>
    @include('partials.footer')
    @include('partials.scripts')
</body>
</html>