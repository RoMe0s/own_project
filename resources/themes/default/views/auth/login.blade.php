@extends('layouts.master')

@section('content')
    <div class="auth_form col-md-12 text-center">
        <h2>
            {{--<i class="fa fa-sign-in" aria-hidden="true"></i>--}}
            {!! trans('front_messages.auth message') !!}
        </h2>
        {!! Form::open(['url' => route('auth.post.login'), 'method' => 'POST'])!!}
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
            {!! Form::password('password', ['placeholder' => trans('front_labels.password'), 'required', 'title' => trans('front_labels.password')]) !!}
        </div>
        <div class="col-md-8 col-md-offset-2">
            <br />
            {!! Form::submit(trans('front_labels.sign in'), ['class' => 'form-control']) !!}
            <a class="register_popup_button" href="{!! route('auth.get.register') !!}">
                {!! trans('front_labels.sign up') !!}</a>
            /
            <a>
                {!! trans('front_labels.remember password') !!}
            </a>
            @widget__oauth()
        </div>
        {!! Form::close() !!}
    </div>
@endsection