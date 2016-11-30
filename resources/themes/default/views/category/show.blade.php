@extends('layouts.master')

@section('content')
    <div class="col-md-8">
        <div class="category-list">
            <div class="category-item title">
                <h1>
                    {!! $model->name !!}
                </h1>
                <a data-toggle="collapse" href="#categoryCollapse" aria-expanded="false" aria-controls="categoryCollapse">
                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                </a>
                <div class="collapse description" id="categoryCollapse">
                    {!! $model->content !!}
                </div>
            </div>
            @widget__last_news(null, $model->id, $count)
            <div class="text-center load_more">
                <a href="{!! route('category', ['slug' => $model->slug]) !!}" title="{!! trans('front_labels.load more') !!}">
                    <i class="fa fa-circle" aria-hidden="true"></i>
                    <i class="fa fa-circle" aria-hidden="true"></i>
                    <i class="fa fa-circle" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @widget__rightside_menu()
    </div>
    <div class="clearfix"></div>
@endsection