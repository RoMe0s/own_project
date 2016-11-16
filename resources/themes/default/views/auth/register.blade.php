@extends('layouts.master')

@section('content')
    <div class="auth_form col-md-12 text-center">
        <h2>
            {{--<i class="fa fa-user-plus" aria-hidden="true"></i>--}}
            {!! trans('front_messages.register message') !!}
        </h2>
        {!! Form::open(['url' => route('auth.post.register'), 'method' => 'POST'])!!}
        <p class="errors text-danger">
            @if(sizeof($errors))
                {!! $errors->first() !!}
            @endif
        </p>
        <div class="col-md-8 col-md-offset-2">
            <br />
            {!! Form::text('email', null, ['placeholder' => trans('front_labels.email'), 'required', 'title' => trans('front_labels.email')]) !!}
        </div>
        <div class="col-md-8 col-md-offset-2">
            <br />
            {!! Form::text('name', null, ['placeholder' => trans('front_labels.name'), 'required', 'title' => trans('front_labels.name')]) !!}
        </div>
        <div class="col-md-8 col-md-offset-2">
            <br />
            {!! Form::password('password', ['placeholder' => trans('front_labels.password'), 'required', 'title' => trans('front_labels.password')]) !!}
        </div>
        <div class="col-md-8 col-md-offset-2">
            <br />
            {!! Form::password('password_confirmation', ['placeholder' => trans('front_labels.password confirmation'), 'required', 'title' => trans('front_labels.password confirmation')]) !!}
        </div>
        <div class="col-md-8 col-md-offset-2">
            <br />
            {!! Form::submit(trans('front_labels.sign up'), ['class' => 'form-control']) !!}
            <a class="auth_popup_button" href="{!! route('auth.get.login') !!}">
                {!! trans('front_labels.already have?') !!}
            </a>
            @widget__oauth()
        </div>
        {!! Form::close() !!}
    </div>
@endsection