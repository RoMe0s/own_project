<!---header---->
<div class="header">
    <div class="container">
        <div class="logo">
                @if(Request::url() != route('home'))
                    <a href="{!! route('home') !!}">
                        <img src="{!! Theme::asset('images/logo.jpg') !!}" title="" />
                    </a>
                @else
                    <a>
                        <img src="{!! Theme::asset('images/logo.jpg') !!}" title="" />
                    </a>
                @endif
        </div>
        <!---start-top-nav---->
        @widget__menu('top_menu')
    </div>
</div>
<!--/header-->