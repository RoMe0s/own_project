@extends('layouts.master')

@section('content')
            <div class="col-md-8">
                <div class="news-show">
                    <div class="title">
                        <h1>
                            {!! $model->name !!}
                        </h1>
                        <p class="small-info text-right">
                            @if( \Carbon\Carbon::parse($model->publish_at) >= \Carbon\Carbon::now()->addWeek(-1) )
                                {!! \Carbon\Carbon::parse($model->publish_at)->diffForHumans() !!}
                            @else
                                {!! \Carbon\Carbon::parse($model->publish_at)->format('d-m-Y') !!}
                            @endif
                            /
                            {!! isset($model->comments_count) ? $model->comments_count : 0 !!}
                            <i class="fa fa-comments-o" aria-hidden="true"></i>
                        </p>
                    </div>

                <div class="image">
                @if($model->image && is_file(public_path() . $model->image))
                    <img src="{!! $model->image !!}" alt="{!! $model->name !!}">
                    @endif
                </div>
                <div class="news-content">
                    {!! $model->content !!}
                </div>
                    @if(sizeof($model->visible_tags))
                        <ul class="tags-list">
                            @foreach($model->visible_tags as $key => $tag)
                                <li>
                                    <a href="#">
                                        {!! $tag->name !!}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                <div class="news-media">
                    @include('partials.news.media')
                </div>
                @if(sizeof($model->comments))
                    <ul class="comments-list">
                        <h3>
                            {!! trans('front_labels.comment list') !!}
                        </h3>
                        @foreach($model->comments as $key => $comment)
                            <li>
                                @if(isset($comment->user_id))
                                    @if(isset($comment->user->avatar) && is_file(public_path() . $comment->user->avatar))
                                <div class="avatar">
                                    <img src="{!! url($comment->user->avatar) !!}" />
                                </div>
                                    @endif
                                <span class="name">
                                    {!! $comment->user->name !!}
                                </span>
                                @else
                                    <span class="name">
                                        {!! $comment->name !!}
                                    </span>
                                @endif
                                <span class="date">
                                    @if(\Carbon\Carbon::parse($comment->created_at) >= \Carbon\Carbon::now()->addDay(-1))
                                        {!! \Carbon\Carbon::parse($comment->created_at)->diffForHumans() !!}
                                        @else
                                        {!! \Carbon\Carbon::parse($comment->created_at)->format('d-m-Y') !!}
                                        @endif
                                </span>
                                        <a class="answer">
                                            {!! trans('front_labels.answer') !!}
                                        </a>
                                    <div class="comment-content">
                                    {!! $comment->comment !!}
                                        {!! $comment->comment !!}
                                    </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <div class="comment-form">
                    <h3>
                        {!! trans('front_messages.leave a comment') !!}
                    </h3>
                    {!! Form::open(['url' => route('comments.store'), 'method' => 'POST']) !!}
                    @if(!$user)
                    {!! Form::text('name', null, ['placeholder' => trans('front_labels.name'), 'required']) !!}
                    {!! Form::text('email', null, ['placeholder' => trans('front_labels.email'), 'required']) !!}
                    @else
                    {!! Form::hidden('user', $user->id) !!}
                    @endif
                    {{--{!! Form::textarea('message', '<bloquote>123</bloquote>', ['placeholder' => trans('front_labels.message'), 'required', 'contenteditable' => 'true']) !!}--}}
                    <div contenteditable="true" placeholder="{!! trans('front_labels.message') !!}"></div>
                    {!! Form::submit(trans('front_labels.send')) !!}
                    {!! Form::close() !!}
                </div>
                </div>

             <div class="related-news">
                 related-news
             </div>
        </div>
        <div class="col-md-4">
            @widget__rightside_menu()
        </div>
        <div class="clearfix"></div>
@endsection