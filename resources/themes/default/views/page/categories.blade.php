@extends('layouts.master')

@section('content')
    <div class="col-md-8">
        <div class="categories-list">
            <div class="category-item title">
                <h1>
                    {!! trans('front_labels.category list') !!}
                </h1>
            </div>
            @foreach(cache('Category', 'slug')->orderBy('position', 'DESC')->get() as $category)
                <div class="category-item">
                    <a href="{!! route('category', ['slug' => $category->slug]) !!}">
                        <h2>
                            {!! $category->name !!}
                        </h2>
                    </a>
                    <p class="small-info text-right">
                        {!! $category->news->count() !!}
                        <i class="fa fa-files-o" aria-hidden="true"></i>
                    </p>
                    <div class="image">
                        <img src="{!! $category->image !!}" alt="{!! $category->name !!}"/>
                    </div>
                    <div class="short-content">
                        {!! $category->getShortContent() !!}
                    </div>
                    <a href="{!! route('category', ['slug' => $category->slug]) !!}" class="read-more">
                        {!! trans('front_messages.go') !!}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-4">
        @widget__rightside_menu()
    </div>
@endsection