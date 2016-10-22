@extends('layouts.master')

@section('content')
    <div class="auth_form col-md-12 text-center">
        <h2>
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
            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => trans('front_labels.email'), 'required']) !!}
        </div>
        <div class="col-md-8 col-md-offset-2">
            <br />
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('front_labels.password'), 'required']) !!}
        </div>
        <div class="col-md-4 col-md-offset-4">
            <br />
            {!! Form::submit(trans('front_labels.sign in'), ['class' => 'form-control']) !!}
            <br />
        </div>
        {!! Form::close() !!}
    </div>
@endsection