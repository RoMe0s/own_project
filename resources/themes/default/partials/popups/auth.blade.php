<div id="auth_popup">
    <div class="close_button pull-right">
        <a>
            <i class="fa fa-times-circle-o" aria-hidden="true"></i>
        </a>
    </div>
    <div class="clearfix"></div>
    <div class="auth_form col-md-12 text-center">
        <h2>
            {!! trans('front_messages.auth message') !!}
        </h2>
        {!! Form::open(['url' => route('auth.post.login'), 'method' => 'POST'])!!}
        <p class="errors">
            @if(sizeof($errors))
                {!! $errors->first() !!}
            @endif
        </p>
        <div class="col-md-8 col-md-offset-2">
            {!! Form::text('email', null, ['class' => 'required', 'placeholder' => trans('front_labels.email')]) !!}
        </div>
        <div class="col-md-8 col-md-offset-2">
            {!! Form::password('password', ['class' => 'required', 'placeholder' => trans('front_labels.password')]) !!}
        </div>
        <div class="col-md-4 col-md-offset-4">
            {!! Form::submit(trans('front_labels.sign in')) !!}
        </div>
        {!! Form::close() !!}
    </div>
    <div class="clearfix"></div>
    <div class="text-center">
        <a>
            {!! trans('front_labels.sign up') !!}

        </a>
        /
        <a>
            {!! trans('front_labels.remember password') !!}
        </a>
    </div>
</div>