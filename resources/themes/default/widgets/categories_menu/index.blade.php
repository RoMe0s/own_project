@if(sizeof($categories))
<nav class="categories-menu">
    <ul class="container">
        <div class="left-side">
        @foreach($categories as $key => $value)
            @if(route('category', ['slug' =>  $value->slug]) == Request::url())
            <li class="active">
                <a>
                @else
                    <li>
                <a href="{!! route('category', ['slug' =>  $value->slug]) !!}">
                    @endif
                    {!! $value->name !!}
                </a>
            </li>
        @endforeach
        </div>
        <div class="dropdown right-side" title="{!! trans('front_messages.view all in category') !!}">
                <ul class="dropdown-menu">
                </ul>
            <a href="{!! route('pages.show', ['slug' => 'categories']) !!}" data-toggle="dropdown">
                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
            </a>
        </div>
</nav>
@endif