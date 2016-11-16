@extends('layouts.master')

@section('content')
            <div>
                <div class="col-md-9">
                @widget__last_news(null, $count)
                    <div class="text-center load_more" style="padding: 20px 0px; width: 100%;">
                        <a href="{!! route('home', ['count' => $count+5]) !!}" type="button" style="width: 100%; display: block; padding: 5px;">{!! trans('front_labels.load more') !!}</a>
                    </div>
                </div>
                <div class="col-md-3">
                @widget__rightside_menu()
                </div>
                <div class="clearfix"></div>
            </div>
@endsection