<div class="col-md-8 col-md-offset-2">
        {!! Form::text('email', session()->has('register["email"]') ? session('register["email"]') : null, ['class' => 'required', 'placeholder' => trans('front_labels.email')]) !!}
    {!! Form::hidden('first_step', true) !!}
</div>
<div class="col-md-4 col-md-offset-4">
    {!! Form::submit(trans('front_labels.next step')) !!}
</div>