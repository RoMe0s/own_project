@extends('layouts.master')

@section('content')
    <div class="single">
            <div class="col-md-8 content-single">
                <h1>
                    {!! $model->name !!}
                </h1>
                <span>
                    {!! $model->publish_at !!} /
                    {!! $model->comments->count() !!}
                    <i class="fa fa-comments-o" aria-hidden="true"></i>
                </span>
                @if($model->image && is_file(public_path() . $model->image))
                    <img src="{!! $model->image !!}" alt="{!! $model->name !!}" style="max-width: 670px; width:100%;">
                    @endif
                <div class="single-grid">
                    {!! $model->content !!}
                </div>
                <div class="clearfix"></div>
                @if($model->visible_category || $model->visible_tags->count())
                <ul class="comment-list">
                    @if(sizeof($model->visible_tags))
                    <li>
                        <i class="fa fa-tags" aria-hidden="true"></i>
                        @foreach($model->visible_tags as $key => $tag)
                        <a href="#">{!! $tag->name !!}</a><!--
                            -->@if($key < count($model->visible_tags) - 1)<!--
                                -->,
                            @endif
                        @endforeach
                    </li>
                        @endif
                </ul>
                @endif
                <div class="content-form">
                    <h3>{!! trans('front_messages.leave a comment') !!}</h3>
                    {!! Form::open(['url' => route('comments.store'), 'method' => 'POST']) !!}
                    {!! Form::text('name', null, ['placeholder' => trans('front_labels.name'), 'required']) !!}
                    {!! Form::text('email', null, ['placeholder' => trans('front_labels.email'), 'required']) !!}
                    {!! Form::textarea('message', null, ['placeholder' => trans('front_labels.message'), 'required']) !!}
                    {!! Form::submit(trans('front_labels.send')) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        <div class="col-md-4 content-right">
            @widget__rightside_menu()
        </div>
        <div class="clearfix"></div>
    </div>
@endsection