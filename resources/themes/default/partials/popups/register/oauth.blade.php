@extends('layouts.master')

@section('content')
<div id="oauth_popup" class="popup">
    <div class="close_button_noactive pull-right">
        <a href="{!! route('home') !!}">
            <i class="fa fa-times-circle-o" aria-hidden="true"></i>
        </a>
    </div>
    <div class="clearfix"></div>
    <div class="oauth_form col-md-12 text-center popup-content">
        {!! Form::open(['url' => route('auth.post.register'), 'method' => 'POST'])!!}
        <ul class="oauth_info">
            <li>
                <img src="{!! $result['avatar'] !!}" class="pull-left" />
            </li>
            <li>
                <span class="pull-left">
                    {!! $result['name'] !!}
                </span>
            </li>
        </ul>
        <p class="info">
            {!! trans('front_messages.oauth message') !!}
        </p>
        <p class="errors">
            @if(sizeof($errors))
                {!! $errors->first() !!}
            @endif
        </p>
        <div class="fields">
            <div class="col-md-8 col-md-offset-2">
                {!! Form::text('email', null, ['class' => 'required', 'placeholder' => trans('front_labels.email')]) !!}
            </div>
        </div>
        <div class="col-md-4 col-md-offset-4">
            {!! Form::submit(trans('front_messages.registration completion')) !!}
        </div>
        {!! Form::close() !!}
    </div>
    <div class="clearfix"></div>
    @include('partials.popups.bottom')
</div>
@endsection