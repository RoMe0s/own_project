<div class="rightside-bar">
@if(sizeof($news))
    <div class="block">
        <div class="title">
            {!! trans('front_labels.popular news') !!}
        </div>
        @foreach($news as $item)
        @if(route('news.show', ['slug' => $item->slug]) == url()->current())
            <a class="active">
        @else
            <a href="{!! route('news.show', ['slug' => $item->slug]) !!}">
        @endif
                {!! $item->name !!}
                <div class="short-content">
                    {!! $item->short_content !!}
                </div>
            </a>
        @endforeach
    </div>
@endif
@if(sizeof($tags))
<div class="block">
    <div class="title">
        {!! trans('front_labels.tag list') !!}
    </div>
    <ul class="small-boxes">
        @foreach($tags as $item)
        <li>
            <a href="#" class="col">
                {!! $item['name'] !!}
            </a>
        </li>
        @endforeach
    </ul>
</div>
@endif
@if(sizeof($archive))
    <div class="block">
        <div class="title">
            {!! trans('front_labels.archives') !!}
        </div>
        <ul>
            @foreach($archive as $item)
            <li>
                <a href="#" class="col">
                    {!! $item['name'] !!}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
@endif
</div>