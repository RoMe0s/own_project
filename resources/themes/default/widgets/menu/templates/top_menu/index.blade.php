<div class="mobile-top-panel">
    <div class="menu-mobile-button">
        <a>
            <i class="fa fa-bars" aria-hidden="true"></i>
        </a>
    </div>
    <div class="menu-mobile-user">
        @if(!$user)
            @if(Request::url() != route('auth.get.login'))
                <a href="{!! route('auth.get.login') !!}">
            @else
                <a>
            @endif
                    <i class="fa fa-sign-in" aria-hidden="true"></i>
                    @else
                        <a href="{!! route('profiles.index') !!}">
                            <i class="fa fa-cog" aria-hidden="true"></i>
                        @endif
        </a>
    </div>
    <div class="menu-mobile-search">
        {!! Form::open(['url' => route('search.index'), 'method' => 'GET']) !!}
        {!! Form::text('query', null, ['placeholder' => trans('front_labels.search'), 'required', 'title' => trans('front_labels.search')]) !!}
        <a onclick="$('.menu-mobile-search form').submit();">
            <i class="fa fa-search" aria-hidden="true"></i>
        </a>
        {!! Form::close() !!}
    </div>
</div>
<nav id="top_menu" class="text-center">
        <ul>
            <div class="left_part">
                @if(sizeof($model->visible_items->where('is_dropdown', 0)))
                    @foreach($model->visible_items->where('is_dropdown', 0) as $key => $item)
                        @if(url($item->link) == Request::url())
                            <li class="active">
                        @else
                            <li>
                                @endif
                                <a href="{!! $item->link !!}" title="{!! $item->title !!}">
                                    {!! $item->name !!}
                                </a>
                            </li>
                            @endforeach
                        @endif
            </div>
            <div class="right_part">
                @if(!$user)
                    @if(Request::url() != route('auth.get.login'))
                        <li class="auth-buttons">
                            <a href="{!! route('auth.get.login') !!}" class="auth_popup_button">
                    @else
                        <li class="active auth-buttons">
                            <a>
                                @endif
                                {!! trans('front_labels.sign in') !!}
                            </a>
                        </li>
                        @if(Request::url() != route('auth.get.register'))
                            <li class="auth-buttons">
                                <a href="{!! route('auth.get.register') !!}" class="register_popup_button">
                        @else
                            <li class="active auth-buttons">
                                <a>
                                    @endif
                                    {!! trans('front_labels.sign up') !!}
                                </a>
                            </li>
                            @else
                                @if(Request::url() != route('profiles.index'))
                                    <li class="auth-buttons">
                                        <a href="{!! route('profiles.index') !!}">
                                @else
                                    <li class="active auth-buttons">
                                        <a>
                                            @endif
                                            {!! trans('front_labels.profile') !!}
                                        </a>

                                    </li>
                                    <li class="auth-buttons">
                                        <a href="{!! route('auth.logout') !!}">
                                            {!! trans('front_labels.sign out') !!}
                                        </a>
                                    </li>
                                @endif
                                <li class="search_form">
                                    {!! Form::open(['url' => route('search.index'), 'method' => 'GET']) !!}
                                    {!! Form::text('query', null, ['placeholder' => trans('front_labels.search'), 'required', 'title' => trans('front_labels.search')]) !!}
                                    {!! Form::close() !!}
                                </li>
                                <li class="show_search">
                                    <a href="#" class="blacked">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li class="dropdown">
                                    @if(sizeof($model->visible_items->where('is_dropdown', 1)))
                                        <ul class="dropdown-menu">
                                            @foreach($model->visible_items->where('is_dropdown', 1) as $item)
                                                @if($item->link == Request::path())
                                                    <li class="active">
                                                @else
                                                    <li>
                                                        @endif
                                                        <a href="{!! $item->link !!}" title="{!! $item->title !!}">
                                                            {!! $item->name !!}
                                                        </a>
                                                    </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    <a href="#" class="blacked" data-toggle="dropdown">
                                        <i class="fa fa-bars" aria-hidden="true"></i>
                                    </a>
                                </li>
            </div>
        </ul>
    </nav>
<!---//End-top-nav---->
    <div class="clearfix"></div>
