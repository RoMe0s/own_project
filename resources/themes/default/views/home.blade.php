@extends('layouts.master')

@section('content')
            <div class="single">
                <div class="col-md-8 content-single">
                @widget__last_news(null, $count)
                    <div class="text-center load_more" style="padding: 20px 0px; width: 100%;">
                        <a href="{!! route('home', ['count' => $count+5]) !!}" style="width: 100%; border: 1px solid #BAB6B6; padding: 5px 5px; display: block; text-decoration: none;">{!! trans('front_labels.load more') !!}</a>
                    </div>
                </div>
                <div class="col-md-4 content-right">
                @widget__rightside_menu()
                </div>
                <div class="clearfix"></div>
            </div>
@endsection