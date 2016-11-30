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
    @widget__categories_menu()
        <div class="container content">
            @widget__breadcrumbs($breadcrumbs)
            @yield('content')
        </div>
    <div class="messages">
        @foreach (['error', 'info', 'success'] as $key)
            @if($flashMessages->has($key))
                @include('partials.popups.message', ['status' => $key, 'message' => implode('', $flashMessages->get($key))])
            @endif
        @endforeach
    </div>
    @include('partials.footer')
    @include('partials.scripts')
</body>
</html>