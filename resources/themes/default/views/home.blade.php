@extends('layouts.master')

@section('content')
                <div class="col-md-8">
                    @widget__last_news('blocks', null, $count)
                    <div class="clearfix"></div>
                    <div class="text-center load_more">
                        <a href="{!! route('home') !!}" title="{!! trans('front_labels.load more') !!}">
                            <i class="fa fa-circle" aria-hidden="true"></i>
                            <i class="fa fa-circle" aria-hidden="true"></i>
                            <i class="fa fa-circle" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    @widget__rightside_menu()
                </div>
    <div class="clearfix"></div>
@endsection