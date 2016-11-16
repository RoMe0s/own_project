    <div class="col-md-8 col-md-offset-2">
        {!! Form::password('password', ['class' => 'required', 'placeholder' => trans('front_labels.password')]) !!}
    </div>
    <div class="col-md-8 col-md-offset-2">
        {!! Form::password('password_confirmation', ['class' => 'required', 'placeholder' => trans('front_labels.password confirmation')]) !!}
    </div>
    <div class="col-md-8 col-md-offset-2">
        {!! Form::text('name', null, ['class' => 'required', 'placeholder' => trans('front_labels.name')]) !!}
    </div>
    <div class="go_back_button col-md-2 col-md-offset-2">
        <a class="register_popup_button">
            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>
        </a>
    </div>
<div class="col-md-4">
    {!! Form::submit(trans('front_labels.register')) !!}
</div>