<div class="top-menu">
<div class="auth pull-right">
    @if(!$user)
        @if(Request::url() != route('auth.get.login'))
            <a href="{!! route('auth.get.login') !!}" class="col-md-12 text-right auth_popup_button" style="color:black; font-size: 18px; line-height: 19px;">
                @else
                    <a class="col-md-12 text-right auth_popup_button" style="color:black; font-size: 18px; line-height: 19px; pointer-events: none;">
                        @endif
                        <i class="fa fa-sign-in" aria-hidden="true"></i>
                        {!! trans('front_labels.sign in') !!}
                    </a>
                    <a class="col-md-12 text-right" style="font-size:14px; line-height: 15px;">
                        {!! trans('front_labels.sign up') !!}
                    </a>
                    @else
                        <a href="{!! route('auth.logout') !!}" class="col-md-12 text-right" style="color:black; font-size: 18px; line-height: 19px;">
                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                            {!! trans('front_labels.sign out') !!}
                        </a>
                        @if(Request::url() != route('profiles.index'))
                            <a href="{!! route('profiles.index') !!}" class="col-md-12 text-right" style="font-size:14px; line-height: 15px;">
                                @else
                                    <a class="col-md-12 text-right" style="font-size:14px; line-height: 15px;">
                                        @endif
                                        {!! trans('front_labels.profile') !!}
                                    </a>
        @endif
</div>
    <img src="/uploads/test.jpg" style="position: absolute; top:10px; right: 0px; height: 150px; width:350px;" class="joe_sounson">
    <div class="search">
        {!! Form::open(['url' => route('search.index'), 'method' => 'GET']) !!}
        {!! Form::text('search_text', null, ['placeholder' => trans('front_labels.search'), 'required']) !!}
        {!! Form::submit('') !!}
        {!! Form::close() !!}
    </div>
    <span class="menu"></span>
    <ul>
        @if(sizeof($model->visible_items))
            @foreach($model->visible_items as $item)
                @if($item->link != Request::path())
                    <li>
                        <a href="{!! $item->link !!}" title="{!! $item->title !!}">
                            {!! $item->name !!}
                        </a>
                    </li>
                @else
                    <li class="active">
                        <a href="{!! $item->link !!}" title="{!! $item->title !!}">
                            {!! $item->name !!}
                        </a>
                    </li>
                @endif
            @endforeach
        @endif
        <div class="clearfix"> </div>
    </ul>
</div>
<div class="clearfix"></div>
<!---//End-top-nav---->