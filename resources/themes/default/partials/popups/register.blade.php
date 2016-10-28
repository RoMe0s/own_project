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
        <div class="col-md-8 col-md-offset-2">
            {!! Form::text('email', null, ['class' => 'required', 'placeholder' => trans('front_labels.email')]) !!}
        </div>
        <div class="col-md-4 col-md-offset-4">
            {!! Form::submit(trans('front_labels.next step')) !!}
        </div>
        {!! Form::close() !!}
    </div>
    <div class="clearfix"></div>
    <div class="text-center">
        {!! trans('front_labels.already have?') !!}
        <a href="{!! route('auth.get.login') !!}">
            {!! trans('front_labels.sign in') !!}
        </a>
    </div>
    @include('partials.popups.bottom')
</div>