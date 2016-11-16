<div id="register_popup" class="popup">
    <div class="close_button pull-right">
        <a>
            <i class="fa fa-times-circle-o" aria-hidden="true"></i>
        </a>
    </div>
    <div class="clearfix"></div>
    <div class="register_form col-md-12 text-center popup-content">
        <h2>
            {!! trans('front_messages.register message') !!}
        </h2>
        {!! Form::open(['url' => route('auth.post.register'), 'method' => 'POST'])!!}
        <p class="errors">
            @if(sizeof($errors))
                {!! $errors->first() !!}
            @endif
        </p>
        <div class="fields">
            @include('partials.popups.register.first')
        </div>
        {!! Form::close() !!}
    </div>
    <div class="clearfix"></div>
    <div class="text-center links">
        <a class="auth_popup_button" href="{!! route('auth.get.login') !!}">
            {!! trans('front_labels.already have?') !!}
        </a>
        @widget__oauth()
    </div>
    @include('partials.popups.bottom')
</div>